<?php

namespace app\controllers;

use Yii;
use app\models\TimeTrailItem;
use app\models\TimeTrailItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use dosamigos\qrcode\QrCode;
use yii\filters\AccessControl;

/**
 * TimeTrailItemController implements the CRUD actions for TimeTrailItem model.
 */
class TimeTrailItemController extends Controller
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
            ], 'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'update', 'delete', 'create', 'report', 'moveUpDown', 'qrcode'],
                'rules' => [
                    [
                        'allow' => FALSE,
                        'roles'=>['?'],
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>['create'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed();
                        },
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>['qrcode'],
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                ['code' => Yii::$app->request->get('code')]
                            );
                        },
                    ],
                    [
                        'allow' => TRUE,
                        'actions'=>array('index', 'update', 'delete', 'report', 'moveUpDown'),
                        'matchCallback' => function () {
                            return Yii::$app->user->identity->isActionAllowed(
                                NULL,
                                NULL,
                                ['time_trail_item_ID' => Yii::$app->request->get('time_trail_item_ID')]);
                        },
                    ],
                    [
                        'allow' => FALSE,  // deny all users
                        'roles'=> ['*'],
                    ]
                ],
            ]
        ];
    }

    /**
     * Lists all TimeTrailItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TimeTrailItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimeTrailItem model.
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
     * Creates a new TimeTrailItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TimeTrailItem();
        if (Yii::$app->request->post('TimeTrailItem') &&
            $model->load(Yii::$app->request->post())) {
//            $model->time_trail_ID = Yii::$app->request->get('time_trail_id');
//            d(Yii::$app->request->get('time_trail_id'));
//            dd($model->time_trail_ID);
            $model->setNewOrderForTimeTrailItem();
            $model->setUniqueCodeForTimeTrailItem();
            if($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new time trail item.'));
                return $this->redirect(['time-trail/index']);
            }
        } else {
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
                      d(Yii::$app->request->get('time_trail_id'));
            $model->time_trail_ID = Yii::$app->request->get('time_trail_id');
            //$this->setCookieIndexTab($model->date);
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }
//dd($model);
        return $this->render([
            '/time-trail-item/create',
            'model' => $model
        ]);
    }

    /**
     * Updates an existing TimeTrailItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->time_trail_item_ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TimeTrailItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionQrcode($code) {
        $event_id = Yii::$app->user->identity->selected_event_ID;

    	$link = "www.kiwi.run/index.php?r=time-trail-check/create&event_id=".$event_id."&code=".$code;
        return QrCode::jpg(
            $link,
            Yii::$app->params['timetrail_code_path'] . $code . '.jpg',
            1,
            3,
            1,
            TRUE);
    }

    public function actionReport($time_trail_item_ID)
	{
	    $model = $this->findModel($time_trail_item_ID);
        if (isset($model)) {
            $content = $this->renderPartial('reportview', ['model' => $model]);
           // setup kartik\mpdf\Pdf component
           $pdf = new Pdf([
               // set to use core fonts only
               'mode' => Pdf::MODE_CORE,
               // A5 paper format
               'format' => [100, 200],
               'marginLeft' => 0,
               'marginRight' => 0,
               'marginTop' => 0,
               'marginBottom' => 0,
               'defaultFont' => 'arial',
               'filename' => $model->time_trail_item_name,
               // portrait orientation
               'orientation' => Pdf::ORIENT_LANDSCAPE,
               // stream to browser inline
               'destination' => Pdf::DEST_BROWSER,
               // your html content input
               'content' => $content,
               // format content from your own css file if needed or use the
               // enhanced bootstrap css built by Krajee for mPDF formatting
               'cssFile' => 'css/qrreport.css',
               //'@web/css/qrreport.css',
            //   'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
               // any css to be embedded if required
            //    'cssInline' => '.kv-heading-1{font-size:18px}',
                // set mPDF properties on the fly
               'options' => [
                   'title' => Yii::t('app', 'Time trail:') . ' ' . $model->time_trail_item_name,
                   'subject' => Yii::t('app', 'Time trail:') . ' ' . $model->time_trail_item_name,
                //    'keywords' => 'krajee, grid, export, yii2-grid, pdf'
               ],
                // call mPDF methods on the fly
            //    'methods' => [
            //        'SetHeader'=>[$model->qr_name],
            //        'SetFooter'=>[$model->qr_code],
            //    ]
           ]);

            // return the pdf output as per the destination setting
            return $pdf->render();
        }
	}

    /**
     * Finds the TimeTrailItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimeTrailItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TimeTrailItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
