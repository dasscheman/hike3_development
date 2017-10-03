<?php

namespace app\controllers;

use Yii;
use app\models\TimeTrail;
use app\models\TimeTrailCheck;
use app\models\TimeTrailItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\DeelnemersEvent;
use yii\filters\AccessControl;

/**
 * TimeTrailController implements the CRUD actions for TimeTrail model.
 */
class TimeTrailController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'status', 'create', 'update', 'delete'],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    [
                        'allow' => TRUE,
                        'actions' => ['index', 'create'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        },
                        'roles'=>array('@'),
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['status'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'group_ID' => Yii::$app->request->get('group_ID')
                                ]);
                        },
                        'roles'=>array('@'),
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['update', 'delete'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                ['time_trail_ID' => Yii::$app->request->get('time_trail_ID')]);
                        },
                        'roles'=>array('@'),
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all TimeTrail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimeTrailItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = TimeTrail::find()
            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
            ->all();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single TimeTrail model.
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
     * Displays a single TimeTrail model.
     * @param integer $id
     * @return mixed
     */
    public function actionStatus()
    {
        $event_id = Yii::$app->user->identity->selected_event_ID;
        $groupPlayer = DeelnemersEvent::getGroupOfPlayer();
        $timeTrailChecks = TimeTrailCheck::find()
            ->where('event_ID =:event_id AND group_ID =:group_id')
            ->andWhere(['is', 'end_time', null])
            ->params([
                ':event_id' => $event_id,
                ':group_id' => $groupPlayer
            ])
            ->all();
        return $this->render('view', [
            'models' => $timeTrailChecks,
        ]);
    }

    /**
     * Creates a new TimeTrail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TimeTrail();

        if (Yii::$app->request->post('TimeTrail') &&
            $model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new time trail.'));
                return $this->redirect(['time-trail/index']);
            }
        } else {
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render([
            '/time-trail/create',
            'model' => $model
        ]);
    }

    /**
     * Updates an existing TimeTrail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($time_trail_ID)
    {
        $model = $this->findModel($time_trail_ID);


         if (Yii::$app->request->post('update') == 'delete') {
             $exist = TimeTrailItem::find()
                ->where('event_ID=:event_id and time_trail_ID=:time_trail_ID')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':time_trail_ID' => $model->time_trail_ID
                    ])
                ->exists();

            if (!$exist) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted time trail.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete time trail, it contains items which should be removed first.'));
            }
            return $this->redirect(['time-trail/index']);
        }

        if (Yii::$app->request->post('TimeTrail') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes to time trail.'));
                return $this->redirect(['time-trail/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render([
            '/time-trail/update',
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing TimeTrail model.
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
     * Finds the TimeTrail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimeTrail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimeTrail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
