<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\DeelnemersEventSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;

/**
 * DeelnemersEventController implements the CRUD actions for DeelnemersEvent model.
 */
class DeelnemersEventController extends Controller
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
                'only' => ['index','view', 'update', 'delete', 'viewPlayers', 'create'],
                'rules' => [
                    [
                        'allow' => FALSE,  // deny all guest users
                        'roles'=>array('?'),
                    ],
                    array(
                        'allow' => TRUE,
                        'actions'=>array('dynamicrol'),
                        'roles'=>array('@'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('index', 'view', 'update', 'delete', 'viewPlayers', 'create'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(NULL, NULL, ['deelnemers_ID' => Yii::$app->request->get('deelnemers_ID')]);
                        }
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
     * Lists all DeelnemersEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeelnemersEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DeelnemersEvent model.
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
     * Creates a new DeelnemersEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DeelnemersEvent();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->mailer->compose('sendInschrijving', [
                    'mailEventName' => $model->event->event_name,
                    'mailUsersName' => $model->user->username,
                    'mailUsersNameSender' => $model->createUser->username,
                    'mailUsersEmailSender' => $model->createUser->email,
                    'mailRol' => $model->rol,
                    'mailRolText' => DeelnemersEvent::getRolText($model->rol),
                    'mailGroupName' => $model->group_ID,
                ])
                ->setFrom('noreply@biologenkantoor.nl')
                ->setTo($model->user->email)
                ->setSubject('Inschrijving Hike')
                ->send();
                return $this->redirect(['site/overview-organisation']);
            }
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        } else {
            return $this->renderpartial('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DeelnemersEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($deelnemers_ID)
    {
        $model = $this->findModel($deelnemers_ID);
        if ($model->user_ID == Yii::$app->user->identity->id) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'You cannot change your own account'));
            return $this->redirect(['site/index']);
        }
        if (Yii::$app->request->post('submit') == 'delete') {
           try
            {
                $model->delete();
            }
            catch(CDbException $e)
            {
                throw new HttpException(400, Yii::t('app'. 'You cannot remove this player'));
            }

            return $this->redirect(['site/overview-organisation']);
        } elseif ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['site/overview-organisation']);
            }
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        } else {
            return $this->renderPartial('_form', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the DeelnemersEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeelnemersEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeelnemersEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
