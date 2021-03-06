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
use yii\web\Cookie;
use app\models\TimeTrailItem;

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
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'map-update', 'delete'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieOpstart', 'organisatieIntroductie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['status'],
                        'roles' => ['deelnemer', 'organisatie'],
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
        $deelnemersEvent = new DeelnemersEvent();
        $groupPlayer = $deelnemersEvent->getGroupOfPlayer($event_id);
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
            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Nieuwe tijdrit opgeslagen.'));
                return $this->redirect(['open-map/index']);
            }
        } else {
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render(
            '/time-trail/create',
            ['model' => $model]
        );
    }

    /**
     * Without passing parameters this is used to determine what to do after a save.
     * When updating on the map page, the browser tab must be closed.
     *
     * @param type $time_trail_ID
     * @return type
     */
    public function actionMapUpdate($time_trail_ID)
    {
        return $this->actionUpdate($time_trail_ID, true);
    }

    /**
     * Updates an existing TimeTrail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($time_trail_ID, $map = null)
    {
        $model = $this->findModel($time_trail_ID);

        if (Yii::$app->request->post('update') == 'delete') {
            $exist = TimeTrailItem::find()
                ->where('event_ID=:event_id and time_trail_ID=:time_trail_ID')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':time_trail_ID' => $model->time_trail_ID
                ]
                )
                ->exists();

            if (!$exist && Yii::$app->user->can('organisatieOpstart')) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Tijdrit verwijderd.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan tijdrit niet verwijderen, er is al een groep die hem heeft gestart.'));
            }
            if ($map === true) {
                echo "<script>window.close() window.opener.location.reload(true);</script>";
                return;
            }
            return $this->redirect(['time-trail/index']);
        }

        if (Yii::$app->request->post('TimeTrail') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen.'));
                if ($map === true) {
                    echo "<script>window.close();</script>";
                    return;
                }
                return $this->redirect(['time-trail/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render(
                '/time-trail/update',
                ['model' => $model]
        );
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
        $model = TimeTrail::findOne([
                'time_trail_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
