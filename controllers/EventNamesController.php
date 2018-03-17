<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\EventNamesSearch;
use app\models\DeelnemersEvent;
use app\models\Route;
use app\models\Users;
use app\models\Posten;
use app\models\NoodEnvelop;
use app\models\OpenVragen;
use app\models\TimeTrail;
use app\models\TimeTrailItem;
use app\models\Groups;
use app\models\Qr;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use DateTime;

/**
 * EventNamesController implements the CRUD actions for EventNames model.
 */
class EventNamesController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['upload', 'change-status', 'change-settings'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['setMaxTime', 'change-day'],
                        'roles' => ['organisatieGestart'],
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['select-hike', 'create', 'delete'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
     * @deprecated maart 2018
     * Lists all EventNames models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EventNamesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @deprecated maart 2018
     * Displays a single EventNames model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EventNames model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new EventNames();
        $model->status = 1;
        Yii::$app->cache->flush();

        // De gebruiker die de hike aanmaakt moet ook gelijk aangemaakt worden als organisatie
        $modelDeelnemersEvent = new DeelnemersEvent;
        // Het route onderdeel introductie moet ook direct aangemaakt worden.
        // Dit kan later uitgebreid worden met een keuze of de introductie gemaakt moet worden.
        $modelRoute = new Route;

        if ($model->load(Yii::$app->request->post())) {
            $event_id = EventNames::determineNewHikeId();

            // when we have an event_id set the user variable and the cookcie to
            // be sure the before validate is not overwriting with the wrong event_id.
            $modelUser = Users::findOne(Yii::$app->user->identity->id);
            $modelUser->selected_event_ID = $event_id;
            $modelUser->save();
            Yii::$app->user->identity->selected_event_ID = $event_id;
            Yii::$app->user->identity->save();

            $model->attributes = Yii::$app->request->post('EventNames');
            $model->event_ID = $event_id;

            $modelDeelnemersEvent->event_ID = $event_id;
            $modelDeelnemersEvent->user_ID = Yii::$app->user->id;
            $modelDeelnemersEvent->rol = 1;
            $modelDeelnemersEvent->group_ID = NULL;

            //$modelRoute->day_date = '0000-00-00';
            $modelRoute->route_name = "Introductie";
            $modelRoute->event_ID = $event_id;
            $modelRoute->route_volgorde = 1;

            // validate BOTH $model, $modelDeelnemersEvent and $modelRoute.
            $valid = $model->validate();
            $valid = $modelDeelnemersEvent->validate() && $valid;
            $valid = $modelRoute->validate() && $valid;
            if ($valid) {
                // use false parameter to disable validation
                $model->save(false);
                $modelDeelnemersEvent->save(false);
                $modelRoute->save(false);
                $modelEvents = EventNames::find()
                    ->where(['user_ID' => Yii::$app->user->id])
                    ->joinwith('deelnemersEvents');

                // QR record can only be set after the routemodel save.
                // Because route_ID is not available before save.
                // Furthermore it is not a problem when route record is saved and
                // an error occured on qr save. Therefore this easy and fast solution is choosen.
                if (!Qr::qrExistForRouteId($modelRoute->route_ID)) {
                    $qrModel = new Qr;
                    $qrModel->setAttributes([
                        'qr_name' => $modelRoute->route_name,
                        'qr_code' => Qr::getUniqueQrCode(),
                        'event_ID' => $modelRoute->event_ID,
                        'route_ID' => $modelRoute->route_ID,
                        'score' => 5,
                    ]);

                    $qrModel->setNewOrderForQr();
                    // use false parameter to disable validation
                    $qrModel->save(false);
                }

                $begin = new DateTime($model->start_date);
                $end = new DateTime($model->end_date);

                for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                    $day = Yii::$app->setupdatetime->convert($i);
                    // Wanneer er een hike aangemaakt wordt, dan moet er
                    // gecheckt woren of er voor elke dag al een begin aangemaakt is.
                    // Als dat niet het geval is dan moet die nog aangemaakt worden.
                    if (!Posten::startPostExist($day)) {

                        $modelStartPost = new Posten;
                        $modelStartPost->setAttributes([
                            'event_ID' => $model->event_ID,
                            'post_name' => Yii::t('app', 'Start day'),
                            'date' => $day,
                            'post_volgorde' => 1,
                            'score' => 0,
                        ]);
                        $modelStartPost->save();
                    }
                    $dayname = Yii::$app->setupdatetime->getDay($i);
                    // Wanneer er een hike aangemaakt wordt, dan wordt er voor
                    // elke dag een route aangemaakt.
                    if (!Route::routeExistForDay($day)) {

                        $modelRoute = new Route;
                        $modelRoute->setAttributes([
                            'event_ID' => $model->event_ID,
                            'route_name' => $dayname . ' ' . Yii::t('app', 'route'),
                            'day_date' => $day,
                            'route_volgorde' => 1
                        ]);
                        $modelRoute->save();
                    }
                }

                if ($model->status == EventNames::STATUS_gestart) {
                    Yii::$app->session->setFlash(
                        'warning', Yii::t(
                            'app', 'You created a new hike. Here you add players.
                                On the route overview page you can create the route of an hike.
                                Players cannot see this when the hike status is setup.')
                    );
                }
                return $this->redirect(['/site/overview-organisation']);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('/event-names/create', ['model' => $model]);
        }
        return $this->render('/event-names/create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing EventNames model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChangeSettings($event_ID, $action) {
        Yii::$app->cache->flush();
        $model = $this->findModel($event_ID);

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('update', [
                    'model' => $model,
                    'action' => $action,
            ]);
        }

        if (Yii::$app->request->get('action') == 'change_settings' ||
            Yii::$app->request->get('action') == 'set_max_time') {

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not save the changes.'));
            } else {
                Yii::$app->cache->flush();
                if (Yii::$app->request->get('action') === 'change_settings') {
                    $begin = new DateTime($model->start_date);
                    $end = new DateTime($model->end_date);

                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                        $day = Yii::$app->setupdatetime->convert($i);
                        // Wanneer er een hike aangemaakt wordt, dan moet er
                        // gecheckt woren of er voor elke dag al een begin aangemaakt is.
                        // Als dat niet het geval is dan moet die nog aangemaakt worden.
                        if (!Posten::startPostExist($day)) {

                            $modelStartPost = new Posten;
                            $modelStartPost->setAttributes([
                                'event_ID' => $model->event_ID,
                                'post_name' => Yii::t('app', 'Start day'),
                                'date' => $day,
                                'post_volgorde' => 1,
                                'score' => 0,
                            ]);
                            $modelStartPost->save();
                        }

                        $dayname = Yii::$app->setupdatetime->getDay($i);
                        // Wanneer er een hike aangemaakt wordt, dan wordt er voor
                        // elke dag een route aangemaakt.
                        if (!Route::routeExistForDay($day)) {

                            $modelRoute = new Route;
                            $modelRoute->setAttributes([
                                'event_ID' => $model->event_ID,
                                'route_name' => $dayname . ' ' . Yii::t('app', 'route'),
                                'day_date' => $day,
                                'route_volgorde' => 1
                            ]);
                            $modelRoute->save();
                        }
                    }
                }

                if (Yii::$app->request->get('action') == 'change_settings') {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Changes are saved.'));
                }
                if (Yii::$app->request->get('action') == 'set_max_time') {
                    Yii::$app->session->setFlash('success', Yii::t(
                            'app', 'You set the max time. This is the max walking time the groups have to finish.
                        Time spend on a station is not included.'));
                }
            }
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Deletes an existing EventNames model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $event_ID
     * @return mixed
     */
    public function actionDelete($event_ID) {

        $check = DeelnemersEvent::findOne([
                'event_ID' => $event_ID,
                'user_ID' => Yii::$app->user->identity->id]);

        if($check->rol !== DeelnemersEvent::ROL_organisatie) {
            throw new HttpException(400, Yii::t('app' . 'You cannot remove this hike.'));
        }

        $model = EventNames::findOne([
                'event_ID' => $event_ID]);

        try {
            NoodEnvelop::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            OpenVragen::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            Posten::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            Qr::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            TimeTrail::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            TimeTrailItem::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            Route::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            DeelnemersEvent::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            Groups::deleteAll('event_ID = :event_id', [':event_id' => $event_ID]);
            $model->delete();
        } catch (Exception $e) {
            throw new HttpException(400, Yii::t('app' . 'You cannot remove this hike.'));
        }

        Yii::$app->session->setFlash('info', Yii::t('app', 'Removed hike'));
        return $this->redirect(['event-names/select-hike']);
    }

    public function actionUpload() {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $image = UploadedFile::getInstance($model, 'image_temp');

            // store the source file name
            $model->image = $image->name;

            $path = Yii::$app->params['event_images_path'] . $model->image;
            if ($model->save()) {
                $image->saveAs($path);
            }
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeStatus() {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = $model->start_date;

        if ($model->save()) {
            Yii::$app->cache->flush();
            if ($model->status == EventNames::STATUS_opstart) {
                Yii::$app->session->setFlash('warning', Yii::t(
                        'app', 'The hike is has status setup.
                        Users cannot see anything of the hike. They can see the
                        different hike elements when the hike has status introduction or started')
                );
            }
            if ($model->status == EventNames::STATUS_introductie) {
                Yii::$app->session->setFlash('warning', Yii::t(
                        'app', 'The hike is has status introduction.
                        Users can see the questions for the introduction and they
                        can scan the silent stations for the introduction.')
                );
            }
            if ($model->status == EventNames::STATUS_gestart) {
                Yii::$app->session->setFlash('warning', Yii::t(
                        'app', 'The hike is started, active day is set on start date.
                        For this day user can see the questions, scan stations and open hints.
                        Don\'t forget to set the max time if you want to have a time limit.'));
            }
        } else {
            // validation failed: $errors is an array containing error messages
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }

        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeDay() {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = Yii::$app->setupdatetime->storeFormat(Yii::$app->request->post('EventNames')['active_day'], 'date');
        if ($model->save()) {           // validation failed: $errors is an array containing error messages
            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Don\'t forget to set the max time.'));
        } else {
            // validation failed: $errors is an array containing error messages
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }

        return $this->redirect(['site/overview-organisation'], 200);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionSetMaxTime() {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());

        if ($model->validate()) {
            $model->save(FALSE);
            Yii::$app->cache->flush();
            if ($model->status == EventNames::STATUS_gestart) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'The hike is started, active day is set on start date, don\'t forget to set the max time.'));
            }
        } else {
            // validation failed: $errors is an array containing error messages
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }

        return $this->redirect(['site/overview-organisation'], 200);
    }

    public function actionSelectHike() {
        $modelEvents = EventNames::find()
            ->where(['user_ID' => Yii::$app->user->id])
            ->joinwith('deelnemersEvents');
        if (NULL !== Yii::$app->request->get('event_ID')) {
            $modelDeelnemersEvent = DeelnemersEvent::find()
                ->where([
                    'event_ID' => Yii::$app->request->get('event_ID'),
                    'user_ID' => Yii::$app->user->identity->id
                ])
                ->one();

            if(isset($modelDeelnemersEvent->rol) &&
                $modelDeelnemersEvent->rol >= 1 ) {
                Yii::$app->user->identity->selected_event_ID = (int) Yii::$app->request->get('event_ID');
                Yii::$app->user->identity->save();
                Yii::$app->cache->flush();
                return $this->redirect(['/site/index']);
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Computer says no.'));
           }
        }

        return $this->render('select-hike', [
                'modelEvents' => $modelEvents,
        ]);
    }

    /**
     * Finds the EventNames model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EventNames the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = EventNames::findOne([
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
