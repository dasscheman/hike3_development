<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\DeelnemersEventSearch;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * DeelnemersEventController implements the CRUD actions for DeelnemersEvent model.
 */
class DeelnemersEventController extends Controller {

    public function behaviors() {
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
                        'allow' => true,
                        'actions' => ['create', 'update'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @deprecated maart 2018
     * Lists all DeelnemersEvent models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DeelnemersEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @deprecated maart 2018
     * Displays a single DeelnemersEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DeelnemersEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new DeelnemersEvent();

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderpartial('create', [
                    'model' => $model,
            ]);
        }
        $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        if ($model->save()) {
            Yii::$app->mailer->compose('sendInschrijving', [
                    'mailEventName' => $model->event->event_name,
                    'mailUsersName' => $model->user->username,
                    'mailUsersNameSender' => $model->createUser->username,
                    'mailUsersEmailSender' => $model->createUser->email,
                    'mailRol' => $model->rol,
                    'mailRolText' => DeelnemersEvent::getRolText($model->rol),
                    'mailGroupName' => $model->group_ID,
                ])
                ->setFrom(Yii::$app->params["admin_email"])
                ->setTo($model->user->email)
                ->setSubject('Inschrijving Hike')
                ->send();

            Yii::$app->session->setFlash('success', Yii::t('app', '{username} is aan de hike toegevoegd en een bevestigingsmail is verstuurd', ['username' => $model->user->username]));
        } else {
            foreach ($model->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Updates an existing DeelnemersEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($deelnemers_ID) {
        $model = $this->findModel($deelnemers_ID);

        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderpartial('update', [
                    'model' => $model,
            ]);
        }

        if (Yii::$app->request->post('action') == 'delete') {
            try {
                $model->delete();
            } catch (Exception $e) {
                throw new HttpException(400, Yii::t('app' . 'Je kunt deze speler niet verwijderen'));
            }
            Yii::$app->session->setFlash('info', Yii::t('app', '{username} is verwijderd van de hike', ['username' => $model->user->username]));
            return $this->redirect(['site/overview-organisation']);
        }

        if ($model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Wijzigingen opgeslagen'));
            return $this->redirect(['site/overview-organisation']);
        }
        foreach ($model->getErrors() as $error) {
            Yii::$app->session->setFlash('error', Json::encode($error));
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Finds the DeelnemersEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeelnemersEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = DeelnemersEvent::findOne([
                'deelnemers_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
