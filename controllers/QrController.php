<?php

namespace app\controllers;

use Yii;
use app\models\Qr;
use app\models\QrSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\QrCheck;
use kartik\mpdf\Pdf;
use dosamigos\qrcode\QrCode;

/**
 * QrController implements the CRUD actions for Qr model.
 */
class QrController extends Controller {

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
                        'allow' => FALSE,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'qrcode', 'report'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieOpstart', 'organisatieIntroductie'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
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
    public function actionIndex() {
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
    public function actionCreate($route_ID) {
        $model = new Qr();

        if (Yii::$app->request->post('Qr') &&
            $model->load(Yii::$app->request->post())) {
            $model->qr_code = Qr::getUniqueQrCode();
            $model->setNewOrderForQr();

            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new silent station.'));
                return $this->redirect(['route/index']);
            }
        } else {
            $model->route_ID = $route_ID;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render([
                '/qr/create',
                'model' => $model
        ]);
    }

    /**
     * Updates an existing Qr model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $qr_ID
     * @return mixed
     */
    public function actionUpdate($qr_ID) {
        $model = $this->findModel($qr_ID);

        if (Yii::$app->request->post('update') == 'delete') {
            $exist = QrCheck::find()
                ->where('event_ID=:event_id and qr_ID=:qr_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':qr_id' => $model->qr_ID
                ])
                ->exists();
            if (!$exist) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted silent station.'));
            } else {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete silent station, it is already checked by at leas one group.'));
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('Qr') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes to silent station.'));
                return $this->redirect(['route/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render([
                '/qr/update',
                'model' => $model
        ]);
    }

    /*
     * @Depricated maart 2018
     */
    public function actioncreateIntroductie() {
        $model = new Qr;
        if (isset($_GET['event_id'])) {
            $model->qr_name = "Introductie";
            $model->qr_code = Qr::getUniqueQrCode();
            $model->event_ID = $_GET['event_id'];
            $model->route_ID = Route::getIntroductieRouteId($_GET['event_id']);
            $model->qr_volgorde = Qr::getNewOrderForIntroductieQr($_GET['event_id']);
            $model->score = 5;

            if ($model->save())
                ;
            {
                return $this->redirect(array('route/viewIntroductie', 'event_id' => $_GET['event_id']));
            }
        }
    }

    public function actionQrcode($qr_code) {
        $event_id = Yii::$app->user->identity->selected_event_ID;

        $link = Yii::$app->request->hostInfo . Yii::$app->homeUrl . "?r=qr-check/create&event_id=" . $event_id . "&qr_code=" . $qr_code;
        return QrCode::jpg(
                $link, Yii::$app->params['qr_code_path'] . $qr_code . '.jpg', 1, 3, 1, TRUE);
    }

    public function actionReport($qr_ID) {
        $model = $this->findModel($qr_ID);
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
                    'title' => Yii::t('app', 'Silent station:') . ' ' . $model->qr_name,
                    'subject' => Yii::t('app', 'Silent station:') . ' ' . $model->qr_name,
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

    public function actionMoveUpDown() {
        $event_id = $_GET['event_id'];
        $qr_id = $_GET['qr_id'];
        $qr_volgorde = $_GET['volgorde'];
        $up_down = $_GET['up_down'];
        $route_id = Qr::getQrRouteID($qr_id);

        $currentModel = Qr::findByPk($qr_id);

        $criteria = new CDbCriteria;

        if ($up_down == 'up') {
            $criteria->condition = 'event_ID =:event_id AND
									qr_ID !=:id AND
									route_ID=:route_id AND
									qr_volgorde <=:order';
            $criteria->params = array(':event_id' => $event_id,
                ':id' => $qr_id,
                ':route_id' => $route_id,
                ':order' => $qr_volgorde);
            $criteria->order = 'qr_volgorde DESC';
        }
        if ($up_down == 'down') {
            $criteria->condition = 'event_ID =:event_id AND
									qr_ID !=:id AND
								 	route_ID=:route_id AND
									qr_volgorde >:order';
            $criteria->params = array(':event_id' => $event_id,
                ':id' => $qr_id,
                ':route_id' => $route_id,
                ':order' => $qr_volgorde);
            $criteria->order = 'qr_volgorde ASC';
        }
        $criteria->limit = 1;
        $previousModel = Qr::find($criteria);

        $tempCurrentVolgorde = $currentModel->qr_volgorde;
        $currentModel->qr_volgorde = $previousModel->qr_volgorde;
        $previousModel->qr_volgorde = $tempCurrentVolgorde;

        $currentModel->save();
        $previousModel->save();

        if (Route::routeIdIntroduction($currentModel->route_ID)) {
            return $this->redirect(array('route/viewIntroductie',
                    "route_id" => $currentModel->route_ID,
                    "event_id" => $currentModel->event_ID,));
        } else {
            return $this->redirect(array('route/view',
                    "route_id" => $currentModel->route_ID,
                    "event_id" => $currentModel->event_ID,));
        }
    }

    /**
     * Finds the Qr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Qr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
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
