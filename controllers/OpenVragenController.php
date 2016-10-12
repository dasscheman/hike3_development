<?php

namespace app\controllers;

use Yii;
use app\models\OpenVragen;
use app\models\OpenVragenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OpenVragenController implements the CRUD actions for TblOpenVragen model.
 */
class OpenVragenController extends Controller
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
                'only' => ['update', 'delete', 'create', 'view', 'createIntroductie', 'index', 'viewPlayers', 'moveUpDown', 'dynamicRouteOnderdeel'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('dynamicRouteOnderdeel'),
                        'roles'=>array('@'),
                    ),
                    array(	
                        'allow' => TRUE,
                        'actions'=>array('viewPlayers', 'update', 'delete', 'create', 'view', 'createIntroductie', 'index', 'moveUpDown'),
                        'matchCallback'=> Yii::$app->user->identity->isActionAllowed(),
                        'roles'=>array('@'),
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TblOpenVragen models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpenVragenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single TblOpenVragen model.
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
     * Creates a new TblOpenVragen model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OpenVragen();

        if ($model->load(Yii::$app->request->post('OpenVragen'))) {
            
            $model->attributes=$_POST['OpenVragen'];
			$model->event_ID = $_GET['event_id'];
			$model->route_ID = $_GET['route_id'];
			$model->vraag_volgorde = OpenVragen::getNewOrderForVragen(
				$_GET['event_id'],
				$_GET['route_id']);

			if($model->save())
            return $this->redirect([
					'/route/view',
					'event_id'=>$model->event_ID,
                    'route_id'=>$model->route_ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
		
    /**
     * Updates an existing TblOpenVragen model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Route::model()->routeIdIntroduction($model->route_ID)){
                return $this->redirect(array('/route/viewIntroductie',
							'route_id'=>$model->route_ID,
							'event_id'=>$model->event_ID));
            } else {
                return $this->redirect(array('/route/view',
							'route_id'=>$model->route_ID,
							'event_id'=>$model->event_ID));
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
		
    /**
     * Deletes an existing TblOpenVragen model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        }catch (Exception $ex) {
            throw new CHttpException(400,"Je kan deze vraag niet verwijderen.");
        }
		if (Route::routeIdIntroduction($_GET['route_id'])){
			$this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/route/viewIntroductie',
									'event_id'=>$_GET['event_id'],
									'route_id'=>$_GET['route_id']));
		} else {
			$this->redirect(isset($_POST['returnUrl']) ?
					$_POST['returnUrl'] : array('/route/view',
									'event_id'=>$_GET['event_id'],
									'route_id'=>$_GET['route_id']));
        }
    }
    
    /**
     * Finds the TblOpenVragen model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOpenVragen the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpenVragen::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	public function actionViewPlayers()
	{
		$event_id = $_GET['event_id'];
		/**
		 * Alleen de vragen van een active dag van een gestarte hike
		 * kunnen worden getoond. Er worden exeptions gezet als niet
		 * voldaan wordt.
		 */
        $searchModel = new OpenVragenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//		if (EventNames::getStatusHike($event_id) == EventNames::STATUS_introductie) {
//			$openVragenDataProvider=new CActiveDataProvider('OpenVragen',
//				array(
//					'criteria'=>array(
//						 'join'=>'JOIN tbl_route route ON route.route_ID = t.route_ID',
//						 'condition'=>'route.route_name =:name
//										AND route.event_ID =:event_id',
//						 'params'=>array(':name'=>'Introductie',
//										  ':event_id'=>$event_id),
//						 'order'=>'route_ID ASC, vraag_volgorde ASC'
//						),
//					'pagination'=>array(
//						'pageSize'=>30,
//					),
//				)
//			);
//		} else {
//			$active_day = EventNames::getActiveDayOfHike($event_id);
//			$openVragenDataProvider=new CActiveDataProvider('OpenVragen',
//				array(
//					'criteria'=>array(
//						 'join'=>'JOIN tbl_route route ON route.route_ID = t.route_ID',
//						 'condition'=>'route.day_date =:active_day
//										AND route.event_ID =:event_id',
//						 'params'=>array(':active_day'=>$active_day,
//										  ':event_id'=>$event_id),
//						 'order'=>'route_ID ASC, vraag_volgorde ASC'
//						),
//					'pagination'=>array(
//						'pageSize'=>30,
//					),
//				)
//			);
//		}

		$this->render('viewPlayers',array(
			'openVragenDataProvider'=>$openVragenDataProvider,
		));
	}

	public function actionCreateIntroductie()
	{
		$model=new OpenVragen;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OpenVragen']))
		{
			$model->attributes=$_POST['OpenVragen'];
			$model->event_ID = $_GET['event_id'];
			$model->route_ID = Route::model()-> getIntroductieRouteId($_GET['event_id']);
			$model->vraag_volgorde = OpenVragen::model()->getNewOrderForIntroductieVragen($_GET['event_id']);

			if($model->save())
				$this->redirect(array('/route/viewIntroductie', 'event_id'=>$model->event_ID));
		}
		$this->layout='/layouts/column1';
		$this->render('createIntroductie',array(
			'model'=>$model,
		));
	}

	/*
	 * Deze actie wordt gebruikt voor de form velden. Op basis van een hike
	 * en een dag wordt bepaald welke route onderdelen er beschikbaar zijn.
	 * Returns list with available techniek names, for a day and event.
	 */
	public function actionDynamicRouteOnderdeel()
	{
		$day_id = $_POST['day_id'];
		$event_id = $_POST['event_id'];
		$data = Route::findAll('day_ID =:day_id AND event_ID =:event_id',
						array(':day_id'=>$day_id,
						      ':event_id'=>$event_id));
		$mainarr = array();

		foreach($data as $obj)
		{
			//De post naam moet gekoppeld worden aan de post_id:
			$mainarr["$obj->route_techniek_ID"] = RouteTechniek::getRouteTechniekName($obj->route_techniek_ID);
		}

		foreach($mainarr as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
		}
	}

	public function actionMoveUpDown()
    {
		$event_id = $_GET['event_id'];
		$vraag_id = $_GET['vraag_id'];
		$vraag_volgorde = $_GET['volgorde'];
		$up_down = $_GET['up_down'];
		$route_id = OpenVragen::getRouteIdVraag($vraag_id);
		$currentModel = OpenVragen::findByPk($vraag_id);
		$criteria = new CDbCriteria;

		if ($up_down=='up')
		{
			$criteria->condition = 'event_ID =:event_id AND
									open_vragen_ID !=:id AND
									route_ID=:route_id AND
									vraag_volgorde <=:order';
			$criteria->params=array(':event_id' => $event_id,
									':id' => $vraag_id,
									':route_id' => $route_id,
									':order' => $vraag_volgorde);
			$criteria->order= 'vraag_volgorde DESC';
		}
		if ($up_down=='down')
		{
			$criteria->condition = 'event_ID =:event_id AND
									open_vragen_ID !=:id AND
									route_ID=:route_id AND
									vraag_volgorde >=:order';
			$criteria->params=array(':event_id' => $event_id,
									':id' => $vraag_id,
									':route_id' => $route_id,
									':order' => $vraag_volgorde);
			$criteria->order= 'vraag_volgorde ASC';
		}
		$criteria->limit=1;
		$previousModel = OpenVragen::find($criteria);

		$tempCurrentVolgorde = $currentModel->vraag_volgorde;
		$currentModel->vraag_volgorde = $previousModel->vraag_volgorde;
		$previousModel->vraag_volgorde = $tempCurrentVolgorde;

		$currentModel->save();
		$previousModel->save();

		if (Route::routeIdIntroduction($currentModel->route_ID))
		{
			$this->redirect(array('route/viewIntroductie',
			"route_id"=>$currentModel->route_ID,
			"event_id"=>$currentModel->event_ID));
		}
		else
		{
			$this->redirect(array('route/view',
			"route_id"=>$currentModel->route_ID,
			"event_id"=>$currentModel->event_ID,));
		}
	}
}
