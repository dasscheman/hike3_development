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
        $model = TimeTrail::find()->all();
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
//            $model->setNewOrderForTimeTrail();
            if($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new time trail.'));
                return $this->redirect(['time-trail/index']);
            }
        } else {
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            //$this->setCookieIndexTab($model->date);
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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->time_trail_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
