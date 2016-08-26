<?php

namespace app\controllers;

use Yii;
use app\models\Bonuspunten;
use app\models\BonuspuntenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BonuspuntenController implements the CRUD actions for TblBonuspunten model.
 */
class BonuspuntenController extends Controller
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
                // We will override the default rule config with the new AccessRule class
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'only' => ['index', 'view', 'create', 'update', 'delete', 'dynamicpostid', 'viewPlayers'],
                'rules' => [
                    [
                        'actions' => ['dynamicpostid'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['viewPlayers'],
                        'allow' => isset($_GET["group_id"]),
                        // Allow moderators and admins to update
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'delete', 'create'],
                        'allow' => Bonuspunten::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id),
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => Bonuspunten::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            $_GET["id"],
                            $_GET["group_id"]),
                    ],
                    [
                        'actions' => ['viewPlayers'],
                        'allow' => Bonuspunten::isActionAllowed(
                            Yii::app()->controller->id,
                            Yii::app()->controller->action->id,
                            "",
                            $_GET["group_id"]),
                    ],
                    
                ],
            ],
        ];
    }
    
    

    /**
     * Lists all TblBonuspunten models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblBonuspuntenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblBonuspunten model.
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
     * Creates a new TblBonuspunten model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblBonuspunten();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bouspunten_ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblBonuspunten model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->bouspunten_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblBonuspunten model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblBonuspunten model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblBonuspunten the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblBonuspunten::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
// OUD
//
//	/**
//	 * Specifies the access control rules.
//	 * This method is used by the 'accessControl' filter.
//	 * @return array access control rules
//	 */
//
//	/**
//	 * Displays a particular model.
//	 * @param integer $id the ID of the model to be displayed
//	 */
//	public function actionView($id)
//	{
//		$this->render('view',array(
//			'model'=>$this->loadModel($id),
//		));
//	}
//
//	/**
//	 * Creates a new model.
//	 * If creation is successful, the browser will be redirected to the 'view' page.
//	 */
//	public function actionCreate()
//	{
//		$model=new Bonuspunten;
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['Bonuspunten']))
//		{
//			$model->attributes=$_POST['Bonuspunten'];
//			if($model->save())
//				$this->redirect(array('/bonuspunten/index',
//						      'event_id'=>$model->event_ID));;
//		}
//
//		$this->layout='//layouts/column1';
//		$this->render('create',array(
//			'model'=>$model,
//		));
//	}
//
//	/**
//	 * Updates a particular model.
//	 * If update is successful, the browser will be redirected to the 'view' page.
//	 * @param integer $id the ID of the model to be updated
//	 */
//	public function actionUpdate($id)
//	{
//		$model=$this->loadModel($id);
//
//		// Uncomment the following line if AJAX validation is needed
//		// $this->performAjaxValidation($model);
//
//		if(isset($_POST['Bonuspunten']))
//		{
//			$model->attributes=$_POST['Bonuspunten'];
//			if($model->save())
//				$this->redirect(array('/game/groupOverview',
//						      'event_id'=>$model->event_ID,
//						      'group_id'=>$model->group_ID));
//		}
//
//		$this->render('update',array(
//			'model'=>$model,
//		));
//	}
//
//	/**
//	 * Deletes a particular model.
//	 * If deletion is successful, the browser will be redirected to the 'admin' page.
//	 * @param integer $id the ID of the model to be deleted
//	 */
//	public function actionDelete($id)
//	{
//		$this->loadModel($id)->delete();
//
//		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//		if(!isset($_GET['ajax']))
//			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/game/groupOverview',
//												 'event_id'=>$model->event_ID,
//												 'group_id'=>$model->group_ID));
//	}
//
//	/**
//	 * Lists all models.
//	 */
//	public function actionIndex()
//	{
//		$model=new Bonuspunten('search');
//		$this->layout='//layouts/column1';
//
//		$model->unsetAttributes();  // clear any default values
//
//		if(isset($_GET['Bonuspunten']))
//			$model->attributes=$_GET['Bonuspunten'];
//
//		$this->render('index',array(
//			'model'=>$model,
//		));
//	}
//
//	/**
//	 * Manages all models.
//	 */
//	public function actionAdmin()
//	{
//		$model=new Bonuspunten('search');
//		$model->unsetAttributes();  // clear any default values
//		if(isset($_GET['Bonuspunten']))
//			$model->attributes=$_GET['Bonuspunten'];
//
//		$this->render('admin',array(
//			'model'=>$model,
//		));
//	}
//
//	public function actionViewPlayers()
//	{
//		$event_Id = $_GET['event_id'];
//		$group_id = $_GET['group_id'];
//
//		$testwhere = "event_ID = $event_Id AND group_ID = $group_id";
//		$bonuspuntenDataProvider=new CActiveDataProvider('Bonuspunten',
//		    array(
//			 'criteria'=>array(
//				'condition'=>$testwhere,
//			  ),
//			'pagination'=>array(
//			    'pageSize'=>10,
//			),
//		));
//		$this->layout='//layouts/column1';
//		$this->render('viewPlayers',array(
//			'bonuspuntenDataProvider'=>$bonuspuntenDataProvider,
//		));
//	}
//
//	/**
//	 * Returns the data model based on the primary key given in the GET variable.
//	 * If the data model is not found, an HTTP exception will be raised.
//	 * @param integer $id the ID of the model to be loaded
//	 * @return Bonuspunten the loaded model
//	 * @throws CHttpException
//	 */
//	public function loadModel($id)
//	{
//		$model=Bonuspunten::model()->findByPk($id);
//		if($model===null)
//			throw new CHttpException(404,'The requested page does not exist.');
//		return $model;
//	}
//
//	/**
//	 * Performs the AJAX validation.
//	 * @param Bonuspunten $model the model to be validated
//	 */
//	protected function performAjaxValidation($model)
//	{
//		if(isset($_POST['ajax']) && $_POST['ajax']==='bonuspunten-form')
//		{
//			echo CActiveForm::validate($model);
//			Yii::app()->end();
//		}
//	}
//
//	/*
//	 * Deze actie wordt gebruikt voor de form velden. Op basis van een hike
//	 * en een dag wordt bepaald welke posten er beschikbaar zijn.
//	 * TODO: Deze functie wordt vaker gebruikt, dus zou op een
//	 * generieke plek moeten komen.
//	 */
//	public function actionDynamicPostId()
//	{
//		$date =  date("Y-m-d", $_POST['date']);
//		$event_id = $_POST['event_id'];
//
//		$data=Posten::model()->findAll('date =:date AND event_ID =:event_id',
//					       array(':date'=>$date,
//						     ':event_id'=>$event_id));
//	   	$mainarr = array();
//
//		foreach($data as $obj)
//		{
//			//De post naam moet gekoppeld worden aan de post_id:
//			$mainarr["$obj->post_ID"] = Posten::model()->getPostName($obj->post_ID);
//		}
//
//		// Een leeg veld moet mogelijk zijn bij het invoeren van bonuspunten.
//		echo CHtml::tag('option',array('value' => ''),'Posten niet van toepassing...',true);
//		foreach($mainarr as $value=>$name)
//		{
//		    echo CHtml::tag('option', array('value'=>$value), CHtml::encode($name),true);
//		}
//	}
//}
