<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\EventNamesSearch;
use app\models\DeelnemersEvent;
use app\models\Route;
use app\models\Users;
use app\models\Posten;
use app\models\PostPassage;
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
use yii\web\HttpException;
use app\models\OpenMap;

/**
 * EventNamesController implements the CRUD actions for EventNames model.
 */
class EventNamesController extends Controller
{
    public function behaviors()
    {
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
                        'allow' => true,
                        'actions' => ['select-hike', 'create', 'delete'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false, // deny all users
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
    public function actionIndex()
    {
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
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EventNames model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
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
            $modelDeelnemersEvent->group_ID = null;

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
                        'warning',
                        Yii::t(
                            'app',
                            'Je hebt een nieuwe hike aangemaakt.
                            Hier kun je deelnemers en organisatie toevoegen.
                            Op de kaart pagina kun route onderdelen toevoegen aan de dagen van de hike.
                            Zolang de status van de hike is \'Uitzetten\' kunnen de deelnemers niets van de hike zien.'
                        )
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
    public function actionChangeSettings($event_ID, $action)
    {
        Yii::$app->cache->flush();
        $model = $this->findModel($event_ID);

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('update', [
                'model' => $model,
                'action' => $action,
            ]);
        }

        if (Yii::$app->request->get('action') != 'change_settings' &&
            Yii::$app->request->get('action') != 'set_change_status') {
              return $this->redirect(['site/overview-organisation']);
        }
        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Kan wijzigingen niet opslaan.'));
            return $this->redirect(['site/overview-organisation']);
        }

        Yii::$app->cache->flush();
        if (Yii::$app->request->get('action') === 'change_settings') {
            $begin = new DateTime($model->start_date);
            $end = new DateTime($model->end_date);

            for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                  $day = Yii::$app->setupdatetime->convert($i);
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
        if (Yii::$app->request->get('action') === 'set_change_status') {
            if(Yii::$app->request->post('EventNames')['start_all_groups']) {
                $startPost = Posten::getStartPost($model->active_day);
                foreach($model->groups as $group) {
                    $modelPassage = new PostPassage();
                    $modelPassage->post_ID = $startPost;
                    $modelPassage->event_ID = $model->event_ID;
                    $modelPassage->group_ID = $group->group_ID;
                    $modelPassage->gepasseerd = 1;
                    $modelPassage->vertrek = Yii::$app->request->post('EventNames')['start_time_all_groups'];
                    $modelPassage->save();
                    // d($modelPassage);
                    // d($modelPassage->save());
                    // d($modelPassage->getErrors());
                }
            }
        }
        if (Yii::$app->request->get('action') == 'change_settings') {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen zijn opgeslagen.'));
        }
        if (Yii::$app->request->get('action') == 'set_max_time') {
            Yii::$app->session->setFlash('success', Yii::t(
                    'app',
                'Je hebt de tijdslimiet gezet. Dit is de maximum tijd dat groepen vandaag mogen lopen.
                Tijd die doorgebracht wordt op een post wordt niet meegerekend.'
            ));
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Deletes an existing EventNames model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $event_ID
     * @return mixed
     */
    public function actionDelete($event_ID)
    {
        $check = DeelnemersEvent::findOne([
                'event_ID' => $event_ID,
                'user_ID' => Yii::$app->user->identity->id]);

        if ($check->rol !== DeelnemersEvent::ROL_organisatie) {
            throw new HttpException(400, Yii::t('app', 'Je kunt deze hike niet verwijderen.'));
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
            throw new HttpException(400, Yii::t('app', 'Je kunt deze hike niet verwijderen.'));
        }

        Yii::$app->session->setFlash('info', Yii::t('app', 'Hike is verwijderd'));
        return $this->redirect(['event-names/select-hike']);
    }

    public function actionUpload()
    {
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
    public function actionChangeStatus()
    {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt de status niet wijzigen.'));
            return $this->redirect(['site/overview'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = $model->start_date;

        if ($model->save()) {
            Yii::$app->cache->flush();
            if ($model->status == EventNames::STATUS_opstart) {
                Yii::$app->session->setFlash(
                    'warning',
                    Yii::t(
                        'app',
                        'De hike heeft status \'Uitzetten\'.
                        De spelers kunnen nog niets van de hike zien.
                        Ze kunnen de verschillende onderdelen van de hike pas zien als de status \'Introdutie\' of \'Gestart\' is.'
                    )
                );
            }
            if ($model->status == EventNames::STATUS_introductie) {
                Yii::$app->session->setFlash(
                    'warning',
                    Yii::t(
                        'app',
                        'De hike heeft status \'Introductie\'.
                        Spelers kunnen de vragen beantwoorden, stille post scannen en hints openen,
                        maar alleen voor de onderdelen die onder de introductie vallen.'
                    )
                );
            }
            if ($model->status == EventNames::STATUS_gestart) {
                Yii::$app->session->setFlash('warning', Yii::t(
                        'app',
                        'De hike heeft status \'Gestart\', de hike dag is gezet op de eerste dag.
                        Spelers kunnen de vragen beantwoorden, stille post scannen en hints openen,
                        die voor vandaag aangemaakt zijn.
                        Vergeet niet om de tijdslimiet te zetten als deelnemers maar een beperkt aantal uren mogen lopen.'
                    ));
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
    public function actionChangeDay()
    {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt de status niet wijzigen.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = Yii::$app->setupdatetime->storeFormat(Yii::$app->request->post('EventNames')['active_day'], 'date');
        if ($model->save()) {           // validation failed: $errors is an array containing error messages
            Yii::$app->cache->flush();
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Vergeet niet de tijdlimiet te zetten.'));
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
    public function actionSetMaxTime()
    {
        $model = $this->findModel(Yii::$app->user->identity->selected_event_ID);

        if (null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Je kunt de status niet wijzigen.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());

        if ($model->validate()) {
            $model->save(false);
            Yii::$app->cache->flush();
            if ($model->status == EventNames::STATUS_gestart) {
                Yii::$app->session->setFlash('warning', Yii::t(
                    'app',
                    'De hike heeft status \'Gestart\', de hike dag is gezet op de
                    eerste dag. Vergeet niet om de tijdslimiet te zetten.'));
            }
        } else {
            // validation failed: $errors is an array containing error messages
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }

        return $this->redirect(['site/overview-organisation'], 200);
    }

    public function actionSelectHike()
    {
        $modelEvents = EventNames::find()
            ->where(['user_ID' => Yii::$app->user->id])
            ->joinwith('deelnemersEvents');
        if (null !== Yii::$app->request->get('event_ID')) {
            OpenMap::setCookieIndexRoute(null);
            $modelDeelnemersEvent = DeelnemersEvent::find()
                ->where([
                    'event_ID' => Yii::$app->request->get('event_ID'),
                    'user_ID' => Yii::$app->user->identity->id
                ])
                ->one();

            if (isset($modelDeelnemersEvent->rol) &&
                $modelDeelnemersEvent->rol >= 1) {
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
    protected function findModel($id)
    {
        $model = EventNames::findOne([
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
