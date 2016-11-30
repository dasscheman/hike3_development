<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\EventNamesSearch;
use app\models\DeelnemersEvent;
use app\models\Route;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use \yii\helpers\Json;

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
                'only' => ['create','index', 'view', 'update', 'updateImage', 'delete', 'viewPlayers', 'changeStatus', 'changeDay'],
                'rules' => [
                    array(	
                        'actions'=>array('create'),
                        'allow' => TRUE,
                        'roles'=>array('@'),
                    ),
                    array(
                        'actions'=>['index', 'view', 'update', 'updateImage', 'delete', 'viewPlayers', 'changeStatus', 'changeDay'],
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
            $model->attributes=Yii::$app->request->post('EventNames');
            $model->event_ID = EventNames::determineNewHikeId();
            $model->image=UploadedFile::getInstance($model,'image');           
            
            $modelDeelnemersEvent->event_ID = $model->event_ID;
            $modelDeelnemersEvent->user_ID = \Yii::$app->user->id;
            $modelDeelnemersEvent->rol = 1;
            $modelDeelnemersEvent->group_ID = NULL;

            $modelRoute->day_date = NULL;
            $modelRoute->route_name = "Introductie";
            $modelRoute->event_ID = $model->event_ID;
            $modelRoute->route_volgorde = 1;

            // validate BOTH $model, $modelDeelnemersEvent and $modelRoute.
            $valid=$model->validate();
            $valid=$modelDeelnemersEvent->validate() && $valid;
            $valid=$modelRoute->validate() && $valid;
            if($valid)
            {
				$newImageName='event_id=' . $model->event_ID . '-logo.jpg';
                // use false parameter to disable validation
                $model->save(false);
                $modelDeelnemersEvent->save(false);
                $modelRoute->save(false);
				if(isset($model->image) && $model->image != ''){
					$model->image->saveAs('images/event_images/' . $newImageName);
					$model->image = $newImageName;
					EventNames::model()->resizeForReport('images/event_images/' . $model->image, $newImageName);
					$model->save(true);
				}
                
                $this->redirect(array('startup/startupOverview','event_id'=>$model->event_ID));
            }
        }

        return $this->render('/eventnames/create',array('model'=>$model)); 
    }

    /**
     * Updates an existing EventNames model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        if (!isset(Yii::$app->user->identity->selected)) { // No hike set
            throw new \yii\web\HttpException(418, Yii::t('app', 'No hike selected.'));
        }
     
        $model = $this->findModel(Yii::$app->user->identity->selected);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['organisatie/overview']);
        } else {
            return $this->render('/update', [
                'model' => $model,
            ]);
        }
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
    
     /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdateImage($event_id)
    {
        $model=$this->findModel($event_id);
        
        if(null !== Yii::$app->request->post('EventNames')) {
			$model->image=CUploadedFile::getInstance($model,'image');
            $model->attributes=Yii::$app->request->post('EventNames');
			$newImageName='event_id=' . $model->event_ID . '-logo.jpg';

            if($model->save()){
				if (isset($model->image) && $model->image != '') {
					$model->image->saveAs('images/event_images/' . $newImageName);
					$model->image = $newImageName;
					EventNames::model()->resizeForReport('images/event_images/' . $model->image, $newImageName);
					$model->save(false);
				}
                $this->redirect(array('startup/startupOverview','event_id'=>$model->event_ID));
			}
        }
          
        $this->render('update',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeStatus($event_id)
    {
        $model=$this->findModel($event_id);

        if(null !== Yii::$app->request->post('EventNames')) {
            $model->attributes=Yii::$app->request->post('EventNames');
            if($model->save()){
				if ($model->status == EventNames::STATUS_gestart){
					$this->redirect(array('eventNames/changeDay','event_id'=>$model->event_ID));
				} else {
					$this->redirect(array('startup/startupOverview','event_id'=>$model->event_ID));
				}
            }
        }

        $this->render('changeStatus',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangeDay($event_id)
    {
        $model=$this->findModel($event_id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(null !== Yii::$app->request->post('EventNames')) {
            $model->attributes=Yii::$app->request->post('EventNames');
            if($model->save())
                $this->redirect(array('startup/startupOverview','event_id'=>$model->event_ID));
        }

        $this->render('changeDay',array(
            'model'=>$model,
        ));
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
