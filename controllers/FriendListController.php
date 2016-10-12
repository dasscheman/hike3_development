<?php

namespace app\controllers;

use Yii;
use app\models\TblFriendList;
use app\models\TblFriendListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FriendListController implements the CRUD actions for TblFriendList model.
 */
class FriendListController extends Controller
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
                'only' => ['connect', 'accept', 'decline','update', 'delete', 'create'],
                'rules' => [
                    [
                        'allow' => TRUE,
                        'actions'=>array('connect', 'accept', 'decline','update', 'delete', 'create'),
                        'roles'=> array('@'),
                        'matchCallback'=> Yii::$app->user->identity->isActionAllowed(),
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TblFriendList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblFriendListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblFriendList model.
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
     * Creates a new TblFriendList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TblFriendList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->friend_list_ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblFriendList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->friend_list_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblFriendList model.
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
     * Finds the TblFriendList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblFriendList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblFriendList::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(	
			
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new FriendList;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FriendList']))
		{
			$model->attributes=$_POST['FriendList'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->friend_list_ID));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionConnect()
	{
		$friendsWithUser = $_GET['user_id'];
		$modelCurrentUser=new FriendList;

		$modelCurrentUser->user_ID = Yii::app()->user->id;
		$modelCurrentUser->friends_with_user_ID = $friendsWithUser;
		$modelCurrentUser->status = 1;

		$modelNewFriendUser=new FriendList;
		$modelNewFriendUser->user_ID = $friendsWithUser;
		$modelNewFriendUser->friends_with_user_ID = Yii::app()->user->id;
		$modelNewFriendUser->status = 0;
		
		$valid=$modelCurrentUser->validate();
		$valid=$modelNewFriendUser->validate() && $valid;

		if($valid)
		{
			// use false parameter to disable validation
			$modelCurrentUser->save(false);
			$modelNewFriendUser->save(false);
			$this->redirect(array('users/searchFriends'));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['FriendList']))
		{
			$model->attributes=$_POST['FriendList'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->friend_list_ID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionAccept()
	{
		$requstedUserId = $_GET['user_id'];
		$dataRequester = FriendList::model()->find('user_ID =:requestUserId AND
										   friends_with_user_ID =:acceptingUserId',
									 array(':requestUserId'=>$requstedUserId,
										   ':acceptingUserId'=>Yii::app()->user->id));
		$dataAccepter = FriendList::model()->find('user_ID =:requestUserId AND
										   friends_with_user_ID =:acceptingUserId',
									 array(':requestUserId'=>Yii::app()->user->id,
										   ':acceptingUserId'=>$requstedUserId));

		$modelRequester=$this->loadModel($dataRequester->friend_list_ID);
		$modelAccepter=$this->loadModel($dataAccepter->friend_list_ID);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$modelRequester->status=2;
		$modelAccepter->status=2;

		$valid=$modelRequester->validate();
		$valid=$modelAccepter->validate() && $valid;

		if($valid)
		{
			// use false parameter to disable validation
			$modelRequester->save(false);
			$modelAccepter->save(false);
			
		}
		$this->redirect(array('game/viewUser','user_id'=>Yii::app()->user->id));
	}

	public function actionDecline()
	{
		$requstedUserId = $_GET['user_id'];
		$dataAccepter = FriendList::model()->find('user_ID =:requestUserId AND
										   friends_with_user_ID =:acceptingUserId',
									 array(':requestUserId'=>Yii::app()->user->id,
										   ':acceptingUserId'=>$requstedUserId));

		$modelAccepter=$this->loadModel($dataAccepter->friend_list_ID);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$modelAccepter->status=3;

		if($modelAccepter->save())
		{echo "save";
			$this->redirect(array('game/viewUser','user_id'=>Yii::app()->user->id));			
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('FriendList');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new FriendList('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['FriendList']))
			$model->attributes=$_GET['FriendList'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return FriendList the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=FriendList::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param FriendList $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='friend-list-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
