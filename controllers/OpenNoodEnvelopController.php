<?php

namespace app\controllers;

use Yii;
use app\models\OpenNoodEnvelop;
use app\models\OpenNoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\DeelnemersEvent;
use app\models\NoodEnvelop;
use app\models\EventNames;

/**
 * OpenNoodEnvelopController implements the CRUD actions for TblOpenNoodEnvelop model.
 */
class OpenNoodEnvelopController extends Controller
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
                'only' => [],
                'rules' => [
                    array(
                        'allow' => FALSE,
                        'roles'=>array('?'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('open', 'cancel-opening'),
                        'roles'=>array('@'),
                    ),
                    array(
                        'allow' => TRUE,
                        'actions'=>array('create', 'index', 'update', 'delete'),
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        },
                        'roles'=>array('@'),
                    ),
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all TblOpenNoodEnvelop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpenNoodEnvelopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $event_id = Yii::$app->user->identity->selected;
		$startDate=EventNames::getStartDate($event_id);
		$endDate=EventNames::getEndDate($event_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'startDate'=>$startDate,
			'endDate'=>$endDate
        ]);
    }

    /**
     * Displays a single TblOpenNoodEnvelop model.
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
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new OpenNoodEnvelop;
        $modelEnvelop = NoodEnvelop::findOne($id);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'modelEnvelop' => $modelEnvelop,
            ]);
        }
        return $this->render('create', [
             'model' => $model,
             'modelEnvelop' => $modelEnvelop,
        ]);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOpen($id)
    {
        $model = new OpenNoodEnvelop;
        $modelEvent = NoodEnvelop::findOne($id);

        $model->event_ID = Yii::$app->user->identity->selected;
        $model->group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected, Yii::$app->user->id);
        $model->nood_envelop_ID = $id;
        $model->opened = 1;

        if (!$model->save()) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Could not open the hint.'));
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $modelEvent,
            ]);
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCancelOpening($id)
    {
        $modelEvent = NoodEnvelop::findOne($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('_list', [
                'model' => $modelEvent,
            ]);
        }
        return $this->redirect(['site/index']);
    }

    /**
     * Updates an existing TblOpenNoodEnvelop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->open_nood_envelop_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TblOpenNoodEnvelop model.
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
     * Finds the TblOpenNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOpenNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OpenNoodEnvelop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
