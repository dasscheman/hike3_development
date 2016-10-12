<?php

namespace app\controllers;

use Yii;
use app\models\TblOpenNoodEnvelop;
use app\models\TblOpenNoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
     * Creates a new TblOpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $nood_envelop_id=$_GET['nood_envelop_id'];
        $event_id=$_GET['event_id'];
        $group_id=$_GET['group_id'];
        $model = new OpenNoodEnvelop();
        $noodEnvelop = NoodEnvelop::model()->find('nood_envelop_ID =:nood_envelop_Id',
                        array(':nood_envelop_Id' => $nood_envelop_id));

        $model->event_ID = $event_id;
        $model->nood_envelop_ID = $nood_envelop_id;
        $model->group_ID = $group_id;
        $model->opened = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                '/game/groupOverview',
                'event_id'=>$_GET['event_id'],
                'group_id'=>$_GET['group_id']]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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
        if (($model = TblOpenNoodEnvelop::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
