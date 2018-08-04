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
                'rules' => [
                    [
                        'actions' => ['resendPasswordUser', 'create', 'remove', 'unblock'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'delete', 'search-friends', 'search-new-friends', 'search-new-friends-list', 'search-friend-requests', 'update', 'view', 'change-password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
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
        return $this->render('view', [
                'model' => $this->findModel(Yii::$app->user->id),
                'activityFeed' => $feed->getData(),
                'friendRequestData' => $friendRequestData,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
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
        $model = $this->findModel(Yii::$app->user->id);

        if (!$model->load(Yii::$app->request->post()) || !$model->save()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Kan wachtwoord niet wijzigen.'));
        }

        return $this->redirect(['view']);
    }

    public function actionResendPasswordUser()
    {
        $model = Users::findByEmail(Yii::$app->request->post('Users')['email']);

        if (isset($model)) {
            $newWachtwoord = GeneralFunctions::randomString(6);
            $model->password = $newWachtwoord;
            $model->password_repeat = $newWachtwoord;

            if ($model->save()) {
                $emailSend = $model->sendEmailWithNewPassword($newWachtwoord);
                if ($emailSend) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Email is verzonden.'));
                    return $this->redirect(['site/login']);
                } else {
                    throw new CHttpException(400, Yii::t('app', "Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl"));
                }
            }
        }

        if (isset(Yii::$app->request->post('Users')['voornaam']) and
            isset(Yii::$app->request->post('Users')['email']) and ! isset($model)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Onbekende gebruiker en/of email.'));
        }

        $model = new Users;
        return $this->render('updateGetNewPass', [
                'model' => $model,
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
            throw new CHttpException(400, Yii::t('app', 'Je kunt deze gebruiker niet verwijderen.'));
        }


        return $this->redirect(['index']);
    }

    public function actionRemove($id, $email)
    {
        $user = $this->findModel($id);

        if ($user->email === $email) {
            $user->block();
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Your accont is blocked and will be removed within a week.');
            } else {
                Yii::$app->session->setFlash('warning', 'Could not remove user.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Unknown user credentials');
        }
        return $this->render('remove');
    }

    public function actionUnblock($id, $email)
    {
        $user = $this->findModel($id);

        if ($user->email === $email) {
            $user->unblock();
            if ($user->save()) {
                Yii::$app->session->setFlash('success', 'Your accont is unblocked.');
            } else {
                Yii::$app->session->setFlash('warning', 'Could not remove user.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Unknown user credentials');
        }
        return $this->render('remove');
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
