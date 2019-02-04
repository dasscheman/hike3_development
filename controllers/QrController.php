<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\Qr;
use app\models\QrSearch;
use app\models\RouteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\QrCheck;
use kartik\mpdf\Pdf;
use yii\helpers\Json;

/**
 * QrController implements the CRUD actions for Qr model.
 */
class QrController extends Controller
{
    /** 
    * {@inheritdoc}
    */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'ajaxupdate' => ['post'],
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
                        'actions' => ['index', 'update', 'map-update', 'print-pdf', 'print-all-pdf', 'ajaxupdate', 'move-up-down'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieOpstart', 'organisatieIntroductie'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Qr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $event_id = $_GET['event_id'];
        $where = "event_ID = $event_id";

        $searchModel = new QrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new TblQr model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($route_ID)
    {
        $model = new Qr();

        if (Yii::$app->request->post('Qr') &&
            $model->load(Yii::$app->request->post())) {
            $model->qr_code = Qr::getUniqueQrCode();
            $model->setNewOrderForQr();

            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Nieuwe stille post opgeslagen.'));
                return $this->redirect(['open-map/index']);
            }
        } else {
            $model->route_ID = $route_ID;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render(
                '/qr/create',
             ['model' => $model]
        );
    }

    /**
     * Without passing parameters this is used to determine what to do after a save.
     * When updating on the map page, the browser tab must be closed.
     *
     * @param type $qr_ID
     * @return type
     */
    public function actionMapUpdate($qr_ID)
    {
        return $this->actionUpdate($qr_ID, true);
    }

    /**
     * Updates an existing Qr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $qr_ID
     * @return mixed
     */
    public function actionUpdate($qr_ID, $map = null)
    {
        $model = $this->findModel($qr_ID);

        if (Yii::$app->request->post('update') == 'delete') {
            $exist = QrCheck::find()
                ->where('event_ID=:event_id and qr_ID=:qr_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':qr_id' => $model->qr_ID
                ]
                )
                ->exists();
            if (!$exist && Yii::$app->user->can('organisatieOpstart')) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Stille post verwijdered.'));
            } else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan stille post niet verwijderen, er is al een groep die deze post gescand heeft.'));
            }
            if ($map === true) {
                echo "<script>window.close(); window.opener.location.reload(true);</script>";
                return;
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('Qr') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen'));
                if ($map === true) {
                    echo "<script>window.close(); window.opener.location.reload(true);</script>";
                    return;
                }
                return $this->redirect(['route/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render(
                '/qr/update',
                ['model' => $model]
        );
    }

    public function actionPrintPdf($qr_ID)
    {
        $model = $this->findModel($qr_ID);

        $model->qrcode();
        $content = $this->renderPartial('reportview', ['model' => $model]);
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
            'filename' => $model->qr_name,
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
                'title' => Yii::t('app', 'Stille post:') . ' ' . $model->qr_name,
                'subject' => Yii::t('app', 'Stille post:') . ' ' . $model->qr_name,
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

    public function actionPrintAllPdf()
    {
        $models = Qr::findAll(['event_ID' => Yii::$app->user->identity->selected_event_ID]);

        $content = "";
        foreach ($models as $model) {
            $model->qrcode();
            $content .= $this->renderPartial('reportview', ['model' => $model]);
        }

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
            'filename' => 'Silent_stations.pdf',
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
                'title' => Yii::t('app', 'Stille post:') . ' ' . $model->qr_name,
                'subject' => Yii::t('app', 'Stille post:') . ' ' . $model->qr_name,
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

    public function actionMoveUpDown()
    {
        $model = $this->findModel(Yii::$app->request->get('qr_id'));
        $up_down = Yii::$app->request->get('up_down');

        if ($up_down === 'up') {
            $previousModel = Qr::find()
                ->where('event_ID =:event_id and route_ID =:route_ID and qr_volgorde <:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_ID' => $model->route_ID, ':order' => $model->qr_volgorde])
                ->orderBy('qr_volgorde DESC')
                ->one();
        } elseif ($up_down === 'down') {
            $previousModel = Qr::find()
                ->where('event_ID =:event_id AND route_ID =:route_ID AND qr_volgorde >:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_ID' => $model->route_ID, ':order' => $model->qr_volgorde])
                ->orderBy('qr_volgorde ASC')
                ->one();
        }

        // Dit is voor als er een reload wordt gedaan en er is geen previousModel.
        // Opdeze manier wordt er dan voorkomen dat er een fatal error komt.
        if (isset($previousModel)) {
            $tempCurrentVolgorde = $model->qr_volgorde;
            $model->qr_volgorde = $previousModel->qr_volgorde;
            $previousModel->qr_volgorde = $tempCurrentVolgorde;

            if ($model->validate() &&
                $previousModel->validate()) {
                $model->save();
                $previousModel->save();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Kan volgorde niet wijzigen.'));
            }
        }

        $startDate = EventNames::getStartDate(Yii::$app->user->identity->selected_event_ID);
        $endDate = EventNames::getEndDate(Yii::$app->user->identity->selected_event_ID);
        $searchModel = new RouteSearch();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('/route/index', [
                    'searchModel' => $searchModel,
                    'startDate' => $startDate,
                    'endDate' => $endDate]);
        }

        return $this->render('/route/index', [
                'searchModel' => $searchModel,
                'startDate' => $startDate,
                'endDate' => $endDate
        ]);
    }

    public function actionAjaxupdate()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));
        $model->latitude = Yii::$app->request->post('latitude');
        $model->longitude = Yii::$app->request->post('longitude');

        if ($model->save()) {
            return true;
        } else {
            foreach ($model->getErrors() as $error) {
                return Json::encode($error);
            }
        }
    }

    /**
     * Finds the Qr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Qr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Qr::findOne([
                'qr_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
