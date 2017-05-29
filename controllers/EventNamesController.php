<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\EventNamesSearch;
use app\models\DeelnemersEvent;
use app\models\Route;
use app\models\Posten;
use app\models\Qr;
use app\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\helpers\Json;
use yii\helpers\Url;
use app\components\SetupDateTime;
use DateInterval;
use DatePeriod;
use DateTime;

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
                'only' => ['create','index', 'view', 'update', 'upload', 'delete', 'viewPlayers', 'changeStatus', 'selectDay', 'setMaxTime'],
                'rules' => [
                    array(
                        'actions'=>array('create'),
                        'allow' => TRUE,
                        'roles'=>array('@'),
                    ),
                    [
                        'actions'=>['update'],
                        'allow' => TRUE,
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'event_ID' => Yii::$app->request->get('event_ID'),
                                    'action' => Yii::$app->request->get('action')
                                ]);
                        }
                    ],
                    [
                        'actions'=>['index', 'view', 'upload', 'delete', 'viewPlayers', 'changeStatus', 'changeDay', 'selectDay'],
                        'allow' => TRUE,
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['event_ID' => Yii::$app->request->get('event_ID')]);
                        }
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ]
            ]
        ];
    }

    /**
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

        // De gebruiker die de hike aanmaakt moet ook gelijk aangemaakt worden als organisatie
        $modelDeelnemersEvent = new DeelnemersEvent;
        // Het route onderdeel introductie moet ook direct aangemaakt worden.
        // Dit kan later uitgebreid worden met een keuze of de introductie gemaakt moet worden.
        $modelRoute = new Route;

        if ($model->load(Yii::$app->request->post())) {
            $event_id = EventNames::determineNewHikeId();

            // when we have an event_id set the user variable and the cookcie to
            // be sure the before validate is not overwriting with the wrong event_id.
            Yii::$app->user->identity->selected_event_ID = (int) $event_id;
            Yii::$app->user->identity->save();

            $model->attributes = Yii::$app->request->post('EventNames');
            $model->event_ID = $event_id;

            $modelDeelnemersEvent->event_ID = $event_id;
            $modelDeelnemersEvent->user_ID = Yii::$app->user->id;
            $modelDeelnemersEvent->rol = 1;
            $modelDeelnemersEvent->group_ID = NULL;

            $modelRoute->day_date = '0000-00-00';
            $modelRoute->route_name = "Introductie";
            $modelRoute->event_ID = $event_id;
            $modelRoute->route_volgorde = 1;

            // validate BOTH $model, $modelDeelnemersEvent and $modelRoute.
            $valid = $model->validate();
            $valid = $modelDeelnemersEvent->validate() && $valid;
            $valid = $modelRoute->validate() && $valid;
            if($valid)
            {
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

                for($i = $begin; $i <= $end; $i->modify('+1 day')){
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
                             'post_volgorde'=> 1,
                             'score' => 0,
                         ]);
                         $modelStartPost->save();
                     }

                 }

                if ($model->status == EventNames::STATUS_gestart){
                    Yii::$app->session->setFlash(
                        'warning',
                        Yii::t(
                            'app',
                            'You created a new hike. Here you add players.
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
    public function actionUpdate($event_ID, $action)
    {
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
            }  else {
                if (Yii::$app->request->get('action') == 'change_settings') {
                    $begin = new DateTime($model->start_date);
                    $end = new DateTime($model->end_date);

                    for($i = $begin; $i <= $end; $i->modify('+1 day')){
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
                                 'post_volgorde'=> 1,
                                 'score' => 0,
                             ]);
                             $modelStartPost->save();
                         }

                     }
                 }

                if (Yii::$app->request->get('action') == 'change_settings') {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Changes are saved.'));
                }
                if (Yii::$app->request->get('action') == 'set_max_time') {
                    Yii::$app->session->setFlash('success', Yii::t(
                        'app',
                        'You set the max time. This is the max walking time the groups have to finish.
                        Time spend on a station is not included.'));
                }
            }
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Deletes an existing EventNames model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try
        {
            $this->findModel($id)->delete();
        }
        catch(CDbException $e)
        {
            throw new CHttpException(400, Yii::t('app/error', 'You cannot remove this hike'));
        }
        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
            if($model->save()){
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
        $model=$this->findModel(Yii::$app->user->identity->selected_event_ID);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = $model->start_date;

        if ($model->save()) {
            if ($model->status == EventNames::STATUS_opstart){
                Yii::$app->session->setFlash('warning', Yii::t(
                    'app',
                    'The hike is has status setup.
                        Users cannot see anything of the hike. They can see the
                        different hike elements when the hike has status introduction or started')
                );
            }
            if ($model->status == EventNames::STATUS_introductie){
                Yii::$app->session->setFlash('warning', Yii::t(
                    'app',
                    'The hike is has status introduction.
                        Users can see the questions for the introduction and they
                        can scan the silent stations for the introduction.')
                );
            }
            if ($model->status == EventNames::STATUS_gestart){
                Yii::$app->session->setFlash('warning', Yii::t(
                    'app',
                    'The hike is started, active day is set on start date.
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
    public function actionChangeDay()
    {
        $model=$this->findModel(Yii::$app->user->identity->selected_event_ID);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());
        $model->active_day = Yii::$app->setupdatetime->storeFormat(Yii::$app->request->post('EventNames')['active_day'], 'date');
        if ($model->save()) {           // validation failed: $errors is an array containing error messages
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
    public function actionSetMaxTime()
    {
        $model=$this->findModel(Yii::$app->user->identity->selected_event_ID);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['site/overview-organisation'], 404);
        }

        $model->load(Yii::$app->request->post());

        if ($model->validate()) {
            $model->save(FALSE);
            if ($model->status == EventNames::STATUS_gestart){
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
        // EXAMPLE
        $modelEvents = EventNames::find()
            ->where(['user_ID' => Yii::$app->user->id])
            ->joinwith('deelnemersEvents');
        if (NULL !== Yii::$app->request->get('event_ID')  ) {
            Yii::$app->user->identity->selected_event_ID = (int) Yii::$app->request->get('event_ID');
            Yii::$app->user->identity->save();
            return $this->redirect(['/site/index']);
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
        if (($model = EventNames::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
