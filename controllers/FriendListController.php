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
                        'actions'=>array('connect'),
                        'roles'=>array('@'),
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>array('accept', 'decline','update', 'delete', 'create'),
                        'roles'=> array('@'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['friend_list_ID' => Yii::$app->request->get('friend_list_ID')]);
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
     * Lists all FriendList models.
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
     * Displays a single FriendList model.
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
     * Creates a new FriendList model.
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
     * Updates an existing FriendList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($friend_list_ID)
    {
        $model = $this->findModel($friend_list_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'friend_list_ID' => $model->friend_list_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FriendList model.
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
     * Finds the FriendList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FriendList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FriendList::findOne($id)) !== null) {
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
		$friendsWithUser = Yii::$app->request->get('user_id');

		$modelCurrentUser = new FriendList;
		$modelCurrentUser->user_ID = Yii::$app->user->id;
		$modelCurrentUser->friends_with_user_ID = $friendsWithUser;
		$modelCurrentUser->status = FriendList::STATUS_waiting;

		$modelNewFriendUser = new FriendList;
		$modelNewFriendUser->user_ID = $friendsWithUser;
		$modelNewFriendUser->friends_with_user_ID = Yii::$app->user->id;
		$modelNewFriendUser->status = FriendList::STATUS_pending;

		$valid = $modelCurrentUser->validate();
        $valid = $modelNewFriendUser->validate() && $valid;

		if(!$valid)
		{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not send invitation.'));
        } else {
            $modelCurrentUser->save(false);
            $modelNewFriendUser->save(false);
        }
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchNewFriends(Yii::$app->request->queryParams);

        if (Yii::$app->getRequest()->isAjax) {
            return $this->renderPartial('/users/searchNewFriends', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('/users/searchNewFriends', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionAccept()
	{
		$requstedUserId = Yii::$app->request->get('user_id');

		$dataRequester = FriendList::find()
            ->where('user_ID=:requestUserId')
            ->andWhere('friends_with_user_ID =:acceptingUserId')
            ->addParams([
                ':requestUserId' => $requstedUserId,
                ':acceptingUserId' => Yii::$app->user->id])
            ->one();

        $dataAccepter = FriendList::find()
            ->where('user_ID =:requestUserId')
            ->andWhere('friends_with_user_ID =:acceptingUserId')
            ->addParams([
                ':requestUserId' => Yii::$app->user->id,
                ':acceptingUserId' => $requstedUserId])
            ->one();

        $modelRequester = $this->findModel($dataRequester->friend_list_ID);
        $modelAccepter = $this->findModel($dataAccepter->friend_list_ID);

        $modelRequester->status=FriendList::STATUS_accepted;
		$modelAccepter->status=FriendList::STATUS_accepted;

		$valid=$modelRequester->validate();
		$valid=$modelAccepter->validate() && $valid;

		if(!$valid)
		{
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not accept invitation.'));
        } else {
			// use false parameter to disable validation
			$modelRequester->save(false);
			$modelAccepter->save(false);
		}

        return $this->redirect(['users/view']);
	}

	public function actionDecline()
	{
		$requstedUserId = Yii::$app->request->get('user_id');
		$dataAccepter = FriendList::find()
            ->where('user_ID=:requestUserId')
            ->andWhere('friends_with_user_ID =:acceptingUserId')
            ->addParams([
                ':requestUserId' => Yii::$app->user->id,
                ':acceptingUserId' => $requstedUserId])
            ->one();


        $modelAccepter = $this->findModel($dataAccepter->friend_list_ID);

        $modelAccepter->status=FriendList::STATUS_declined;

		if(!$modelAccepter->validate()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not accept invitation.'));
        } else {
			// use false parameter to disable validation
            $modelAccepter->save(FALSE);
		}

        return $this->redirect(['users/view']);
	}
}
