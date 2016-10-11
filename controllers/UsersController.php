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
                        'allow' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed(), 
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
            $emailSend = $model->sendEmailNewAccount();
            if($emailSend)
            {
                Yii::$app->session->setFlash('success', Yii::$app->t('app', 'You created an account and you can logon.'));
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
                        throw new CHttpException(400, Yii::$app->t('app', "Je wachtwoord is gewijzigd, maar helaas is het verzenden van je wachtwoord niet gelukt. Probeer nog eens of stuur een mail hike-app@biologenkantoor.nl"));
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
