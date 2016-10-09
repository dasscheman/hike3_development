<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\GeneralFunctions; 
use yii\filters\AccessControl;

/**
 * UsersController implements the CRUD actions for TblUsers model.
 */
class UsersController extends Controller
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
                'only' => ['resendPasswordUser', 'create', 'searchFriends', 'update', 'view', 'ChangePassword'],
                'rules' => [
                    [
                        'actions' => ['resendPasswordUser', 'create'],
                        'allow' => true,
                        // Allow users, moderators and admins to create
                        'roles' => ['?'],
                    ],
                    [
                        // TODO: might want to change the check for  'update', 'view', 'ChangePassword'.
                        'actions' => ['searchFriends', 'update', 'view', 'ChangePassword'],
                        'allow' => true,
                        'roles'=>array('@'),
                    ],
                    [
                        'actions' => ['index', 'delete', 'updateAdmin'],
                        'allow' => Users::isActionAllowed(
                                        Yii::$app->controller->id,
                                        Yii::$app->controller->action->id), 
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all TblUsers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionSearchFriends()
    {
        $model=new UsersSearch();
        $model->unsetAttributes(); // clear any default values
        Yii::$app->request->get();
        return $this->render('searchFriends', [
            'model'=>$model,
        ]);
    }
    
    /**
     * Displays a single TblUsers model.
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
     * Creates a new TblUsers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        if ($model->load(Yii::$app->request->post()) && $model->save()){
            $emailSend = Users::sendEmailWithNewPassword($model, $model->password);
            if($emailSend)
            {
                return $this->redirect(['/site/login']);
            } else {
                throw new \yii\web\HttpException(400, Yii::t('app/error', 'Your account is created, but unfortunately we could not send an email with details. Contact hike-app@biologenkantoor.nl'));
            }
            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TblUsers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->password_repeat = $model->password;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/game/viewUser']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangePassword()
    {
        $model=$this->findModel(Yii::$app->user->id);
 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/game/viewUser']);
        }

        return $this->render('changePassword', [
            'model'=>$model,
        ]);
    }	
    
    public function actionResendPasswordUser()
    {		
        $tempModel=new Users;
        if(Yii::$app->request->post())
        {
            $newModel = Users::find('username =:username AND email =:email',[
                                        ':username' => $tempModel->username,
                                        ':email' => $tempModel->email
                                    ]);
            
            if (isset($newModel)) {
                $newWachtwoord = GeneralFunctions::randomString(4);
                $model=$this->findModel($newModel->user_ID);
                $model->password =$newWachtwoord;
                $model->password_repeat = $newWachtwoord;
                if($model->save()){
                    $emailSend = Users::sendEmailWithNewPassword($model, $newWachtwoord);
                    if($emailSend)
                    {
                        $this->redirect(array('site/index'));
                    } else {
                            throw new CHttpException(400,"Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl");
                    }
                }
            }
        }
        return $this->render('updateGetNewPass', [
            'model'=>$tempModel,
        ]);
    }

    /**
     * Deletes an existing TblUsers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->findModel($id)->delete();
        } catch (Exception $ex) {
            throw new CHttpException(400, Yii::t('app/error', 'You cannot remove this user.'));
        }
        

        return $this->redirect(['index']);
    }
    
    /**
     * Finds the TblUsers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUsers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
		//$model=new Users;
		$model=new Users('create');
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];

			$newWachtwoord = GeneralFunctions::randomString(4);
			$model->password =$newWachtwoord;
			$model->password_repeat = $newWachtwoord;
			if($model->save())
            {
				$emailSend = Users::model()->sendEmailWithNewPassword($model, $newWachtwoord);
				if($emailSend)
				{
					$this->redirect(array('site/index'));
				} else {
					throw new CHttpException(400,"Je account is aangemaakt, maar helaas is het verzenden van je wachtwoord niet gelukt. Stuur een mail hike-app@biologenkantoor.nl");
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionChangePassword()
	{
		$this->layout='//layouts/column1';
		$model=$this->loadModel(Yii::app()->user->id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('/game/viewUser'));
		}

		$this->render('changePassword',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$this->layout='//layouts/column1';

		$model=$this->loadModel(Yii::app()->user->id);
		$model->password_repeat = $model->password;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('/game/viewUser'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	public function actionResendPasswordUser()
	{		
		$tempModel=new Users;
		if(isset($_POST['Users']))
		{
			$tempModel->attributes=$_POST['Users'];
			$newModel = Users::model()->find('username =:username AND email =:email',
											 array(':username' => $tempModel->username,
												   ':email' => $tempModel->email));
			if (isset($newModel)) {
				$newWachtwoord = GeneralFunctions::randomString(4);
				$model=$this->loadModel($newModel->user_ID);
				$model->password =$newWachtwoord;
				$model->password_repeat = $newWachtwoord;
				if($model->save()){
					$emailSend = Users::model()->sendEmailWithNewPassword($model, $newWachtwoord);
				
					if($emailSend)
					{
						$this->redirect(array('site/index'));
					} else {
						throw new CHttpException(400,"Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl");
					}
				}
			}
		}
		$this->render('updateGetNewPass',array(
			'model'=>$tempModel,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		try
		{
			$this->loadModel($id)->delete();
		}
		catch(CDbException $e)
		{		
			throw new CHttpException(400,"Je kan deze gebruiker niet verwijderen.");
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
	
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes(); // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionSearchFriends()
	{
		$model=new Users('search');
		$model->unsetAttributes(); // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];
		
		$this->layout='//layouts/column1';
		$this->render('searchFriends',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Users the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Users $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
