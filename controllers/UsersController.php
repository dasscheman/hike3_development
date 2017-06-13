<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\UsersSearch;
use app\models\FriendList;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\GeneralFunctions;
use yii\filters\AccessControl;
use app\models\ProfileActivityFeed;
use yii\data\ActiveDataProvider;

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
                'only' => ['resendPasswordUser', 'create', 'index', 'delete', 'search-friends', 'search-new-friends', 'search-friend-requests', 'update', 'view', 'ChangePassword'],
                'rules' => [
                    [
                        'actions' => ['resendPasswordUser', 'create'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'delete', 'search-friends', 'search-new-friends', 'search-friend-requests', 'update', 'view', 'ChangePassword'],
                        'allow' => TRUE,
                        'matchCallback' => function () {
                            return Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['user_ID' => Yii::$app->request->get('id')]);
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
     * Lists all Users models.
     * @return mixed
     */
    public function actionSearchNewFriends()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchNewFriends(Yii::$app->request->post());

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('search-new-friends', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('search-new-friends', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionSearchFriends()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchFriends(Yii::$app->request->queryParams);

        return $this->render('searchFriends', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionSearchFriendRequests()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchFriendRequests(Yii::$app->request->queryParams);

        return $this->render('searchFriendRequests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TblUsers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {

        $feed = new ProfileActivityFeed;
        $feed->pageSize = 10;

        $query = Users::find();
        $queryFriendList = FriendList::find();

        $queryFriendList->where('user_ID=:user_id')
            ->addParams([':user_id' => Yii::$app->user->id])
            ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_pending]);

        $friendRequestData = new ActiveDataProvider([
            'query' => $queryFriendList,
        ]);

        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchNewFriends(Yii::$app->request->queryParams);
        // d(Yii::$app->request->get());
        //
        //         d(Yii::$app->request->post());
        //  dd(Yii::$app->request->queryParams);
        return $this->render('view', [
            'model' => $this->findModel(Yii::$app->user->id),
            'activityFeed' => $feed->getData(),
            'friendRequestData' => $friendRequestData,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
            $emailSend = $model->sendEmailNewAccount();
            if($emailSend)
            {
                Yii::$app->session->setFlash('success', Yii::t('app', 'You created an account and you can logon.'));
                return $this->redirect(['/site/login']);
            } else {
                throw new \yii\web\HttpException(400, Yii::t('app', 'Your account is created, but unfortunately we could not send an email with details. Contact hike-app@biologenkantoor.nl'));
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view']);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('/users/update', ['model' => $model]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionChangePassword()
    {
        $model=$this->findModel(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not change password.'));
        }

        return $this->redirect(['view']);
    }

    public function actionResendPasswordUser()
    {
        $model = Users::find()
            ->where('voornaam =:voornaam AND email =:email')
            ->addParams([
                ':voornaam' => Yii::$app->request->post('Users')['voornaam'],
                ':email' => Yii::$app->request->post('Users')['email']
            ])
            ->one();

        if (isset($model)) {
            $newWachtwoord = GeneralFunctions::randomString(6);
            $model->password =$newWachtwoord;
            $model->password_repeat = $newWachtwoord;

            if($model->save()){
                $emailSend = $model->sendEmailWithNewPassword($newWachtwoord);
                if($emailSend)
                {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Email is send.'));
                    return $this->redirect(['site/login']);
                } else {
                    throw new CHttpException(400, Yii::t('app', "Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl"));
                }
            }
        }

        if (isset(Yii::$app->request->post('Users')['voornaam']) AND
            isset(Yii::$app->request->post('Users')['email']) AND
            !isset($model)) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Unknown user and/or email.'));
        }

        $model = new Users;
        return $this->render('updateGetNewPass', [
            'model'=>$model,
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
            throw new CHttpException(400, Yii::t('app', 'You cannot remove this user.'));
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
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
}
