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
use app\models\ProfileActivityFeed;

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
                            return Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed();
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
     * Lists all TblUsers models.
     * @return mixed
     */
    public function actionSearchNewFriends()
    {
        Yii::$app->session->removeAllFlashes();
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->searchNewFriends(Yii::$app->request->queryParams);

        return $this->render('searchNewFriends', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Manages all models.
     */
    public function actionSearchFriends()
    {
        Yii::$app->session->removeAllFlashes();
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
        Yii::$app->session->removeAllFlashes();
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

        return $this->render('view', [
            'model' => $this->findModel(Yii::$app->user->id),
            'activityFeed' => $feed->getData(),
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
            $model->birthdate = Yii::$app->setupdatetime->storeFormat($model->birthdate, 'date');
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
        $model->load(Yii::$app->request->post());
        $model->scenario = Users::SCENARIO_UPDATE;
        if ($model->save()) {
            return $this->render('view', [
                'model' => $this->findModel(Yii::$app->user->id),
            ]);
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

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not change password.'));
        }

        return $this->render('view', [
            'model'=>$model,
        ]);
    }

    public function actionResendPasswordUser()
    {
        $model = new Users;
        if($model->load(Yii::$app->request->post()))
        {
//            $newModel = Users::find('username =:username AND email =:email',[
//                                        ':username' => $model->username,
//                                        ':email' => $model->email
//                                    ]);

            if (isset($model)) {
                $newWachtwoord = GeneralFunctions::randomString(4);
                //$model=$this->findModel($newModel->user_ID);
                $model->password =$newWachtwoord;
                $model->password_repeat = $newWachtwoord;
                if($model->save()){
                    $emailSend = Users::sendEmailWithNewPassword($model, $newWachtwoord);
                    if($emailSend)
                    {
                        $this->redirect(array('site/index'));
                    } else {
                        throw new CHttpException(400, Yii::t('app', "Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl"));
                    }
                }
            }
        }
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
            throw new CHttpException(400, Yii::t('app/error', 'You cannot remove this user.'));
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
