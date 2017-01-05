<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\EventNamesSearch;
use app\models\DeelnemersEvent;
use app\models\Route;
use app\models\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use \yii\helpers\Json;
use yii\helpers\Url;

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
                        'actions'=>array('create', 'selectDay', 'setMaxTime'    ),
                        'allow' => TRUE,
                        'roles'=>array('@'),
                    ),
                    array(
                        'actions'=>['index', 'view', 'update', 'upload', 'delete', 'viewPlayers', 'changeStatus', 'changeDay'],
                        'allow' => TRUE,
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
                    ),
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
        $model->start_date = date('Y-m-d');
        $model->end_date = date('Y-m-d');
        
        // De gebruiker die de hike aanmaakt moet ook gelijk aangemaakt worden als organisatie
        $modelDeelnemersEvent = new DeelnemersEvent;
        // Het route onderdeel introductie moet ook direct aangemaakt worden.
        // Dit kan later uitgebreid worden met een keuze of de introductie gemaakt moet worden.
        $modelRoute = new Route;
        
        if ($model->load(Yii::$app->request->post())) {
            $model->attributes = Yii::$app->request->post('EventNames');
            $model->event_ID = EventNames::determineNewHikeId();
            $model->image=UploadedFile::getInstance($model,'image');           


//            dd($model);

            $modelDeelnemersEvent->event_ID = $model->event_ID;
            $modelDeelnemersEvent->user_ID = \Yii::$app->user->id;
            $modelDeelnemersEvent->rol = 1;
            $modelDeelnemersEvent->group_ID = NULL;

            $modelRoute->day_date = NULL;
            $modelRoute->route_name = "Introductie";
            $modelRoute->event_ID = $model->event_ID;
            $modelRoute->route_volgorde = 1;

            // validate BOTH $model, $modelDeelnemersEvent and $modelRoute.
            $valid = $model->validate();
            $valid = $modelDeelnemersEvent->validate() && $valid;
            $valid = $modelRoute->validate() && $valid;
            if($valid)
            {
				$newImageName='event_id=' . $model->event_ID . '-logo.jpg';
                // use false parameter to disable validation
                $model->save(false);
                $modelDeelnemersEvent->save(false);
                $modelRoute->save(false);
                $modelEvents = EventNames::find()
                     ->where(['user_ID' => Yii::$app->user->id])
                     ->joinwith('deelnemersEvents');

                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('/event-names/select-hike', [
                        'modelEvents' => $modelEvents]);
                    }
                return $this->render('/event-names/select-hike', [
                    'modelEvents' => $modelEvents
                ]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/event-names/create', ['model' => $model]);
        }

        return $this->render('/event-names/create',array('model'=>$model));
    }

    /**
     * Updates an existing EventNames model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->identity->selected);

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                throw new \yii\web\HttpException(400, Yii::t('app', 'cannot save record'));
             }
        }
        return $this->redirect(['organisatie/overview']);
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
        $model = $this->findModel(Yii::$app->user->identity->selected);

        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $image = UploadedFile::getInstance($model, 'image_temp');

            // store the source file name
            $model->image = $image->name;

            $path = Yii::$app->basePath . ''. Yii::$app->params['event_images_path'] . $model->image;
            if($model->save()){
                $image->saveAs($path);
             }
        }
        return $this->redirect(['organisatie/overview']);
    }


    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeStatus()
    {
        $model=$this->findModel(Yii::$app->user->identity->selected);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['organisatie/overview'], 404);
        }

        $model->load(Yii::$app->request->post());
        if ($model->status != EventNames::STATUS_gestart) {
            $model->active_day = $model->start_date;
        }

        if ($model->save()) {
            if ($model->status == EventNames::STATUS_gestart){
                Yii::$app->session->setFlash('warning', Yii::t('app', 'The hike is started, active day is set on start date, don\'t forget to set the max time.'));
            }
        } else {
            // validation failed: $errors is an array containing error messages
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }
        
        return $this->redirect(['organisatie/overview'], 200);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeDay()
    {
        $model=$this->findModel(Yii::$app->user->identity->selected);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['organisatie/overview'], 404);
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

        return $this->redirect(['organisatie/overview'], 200);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionSetMaxTime()
    {
        $model=$this->findModel(Yii::$app->user->identity->selected);

        if(null === Yii::$app->request->post('EventNames')) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Can not change status.'));
            return $this->redirect(['organisatie/overview'], 404);
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

        return $this->redirect(['organisatie/overview'], 200);
    }

    public function actionSelectHike() {
        // EXAMPLE
        $modelEvents = EventNames::find()
            ->where(['user_ID' => Yii::$app->user->id])
            ->joinwith('deelnemersEvents');

        if (NULL !== Yii::$app->request->get('id')  ) {
            Yii::$app->user->identity->setSelected(Yii::$app->request->get('id'));
            Yii::$app->user->identity->setSelectedCookie(Yii::$app->request->get('id'));
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
