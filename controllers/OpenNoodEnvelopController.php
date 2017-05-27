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
                    [
                        'allow' => TRUE,
                        'actions' => ['open'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                [
                                    'nood_envelop_ID' => Yii::$app->request->get('nood_envelop_ID'),
                                    'group_ID' => Yii::$app->request->get('group_ID')
                                ]);
                        },
                        'roles'=>array('@'),
                    ],
                    [
                        'allow' => TRUE,
                        'actions' => ['index', 'update', 'delete'],
                        'matchCallback'=> function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                ['open_nood_envelop_ID' => Yii::$app->request->get('open_nood_envelop_ID')]);
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
     * Lists all TblOpenNoodEnvelop models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpenNoodEnvelopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $event_id = Yii::$app->user->identity->selected_event_ID;
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
    public function actionOpen($nood_envelop_ID)
    {
        $model = new OpenNoodEnvelop;
        $modelEnvelop = NoodEnvelop::findOne($nood_envelop_ID);
        if (!$model->load(Yii::$app->request->post())) {
            return $this->renderPartial('open', [
                'model' => $model,
                'modelEnvelop' => $modelEnvelop,
            ]);
        }
        if (Yii::$app->request->post('submit') == 'open-hint') {
            $model->group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
            $model->opened = 1;

            if (!$model->save()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not open the hint.'));
            }  else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Hint is opened.'));
            }
        }

        return $this->redirect(['site/overview-players']);
    }

    /**
     * Updates an existing TblOpenNoodEnvelop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($open_nood_envelop_ID)
    {
        $model = $this->findModel($open_nood_envelop_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'open_nood_envelop_ID' => $model->open_nood_envelop_ID]);
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
