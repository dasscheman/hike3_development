<?php

namespace app\controllers;

use Yii;
use app\models\FriendList;
use app\models\FriendListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Users;
use app\models\UsersSearch;

/**
 * FriendListController implements the CRUD actions for FriendList model.
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
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        }
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
        $searchModel = new FriendListSearch();
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
        $model = new FriendList();

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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionConnect()
	{
        var_dump("sad;fklas;kfjlk;saj");
		$friendsWithUser = Yii::$app->request->get('user_id');
        
		$modelCurrentUser=new FriendList;
		$modelCurrentUser->user_ID = Yii::$app->user->id;
		$modelCurrentUser->friends_with_user_ID = $friendsWithUser;
		$modelCurrentUser->status = FriendList::STATUS_waiting;

		$modelNewFriendUser=new FriendList;
		$modelNewFriendUser->user_ID = $friendsWithUser;
		$modelNewFriendUser->friends_with_user_ID = Yii::$app->user->id;
		$modelNewFriendUser->status = FriendList::STATUS_pending;
		
		$valid=$modelCurrentUser->validate();
        $valid=$modelNewFriendUser->validate() && $valid;
        Yii::$app->session->removeAllFlashes();
		if(!$valid)
		{   
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not send invitation.'), TRUE);
        } else {
            $modelCurrentUser->save(false);
            $modelNewFriendUser->save(false);
        }
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchNewFriends(Yii::$app->request->post());

        if (Yii::$app->getRequest()->isAjax) {
            return $this->renderPartial('/users/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
        return $this->render('/users/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
}
