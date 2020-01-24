<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Qr;
use app\models\QrCheck;
use app\models\QrCheckSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * QrCheckController implements the CRUD actions for QrCheck model.
 */
class QrCheckController extends Controller
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
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'update'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['deelnemerIntroductie', 'deelnemerGestartTime'],
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
     * Lists all QrCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QrCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new QrCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $qr_code = Yii::$app->request->get('qr_code');
        $even_id = Yii::$app->request->get('event_id');

        $now = date('Y-m-d H:i:s');

        $qr = Qr::find()
            ->where('event_ID =:event_id AND qr_code =:qr_code')
            ->params([
                ':event_id' => $even_id,
                ':qr_code' => $qr_code])
            ->one();

        if (!isset($qr->qr_code)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Dit is geen geldige QR code.'));
            return $this->redirect(['site/overview-players']);
        }

        if ($qr->event_ID != Yii::$app->user->identity->selected_event_ID) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze QR is niet voor deze hike.'));
            return $this->redirect(['site/overview-players']);
        }
        $deelnemersEvent = new DeelnemersEvent();
        $groupPlayer = $deelnemersEvent->getGroupOfPlayer($qr->event_ID);

        if (!$groupPlayer) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Dit is geen geldige QR code.'));
            return $this->redirect(['site/index']);
        }

        if (isset($qr->route->start_datetime) && $qr->route->start_datetime > $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze QR code hoort bij routeonderdeel die nog niet gestart is.'));
          return $this->redirect(['site/overview-players']);
        }

        if (isset($qr->route->end_datetime) && $qr->route->end_datetime < $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze QR code hoort bij routeonderdeel die al afegelopen is..'));
          return $this->redirect(['site/overview-players']);
        }

        $qrCheck = QrCheck::find()
            ->where('event_ID =:event_id AND qr_ID =:qr_id AND group_ID =:group_id')
            ->params([
                ':event_id' => $qr->event_ID,
                ':qr_id' => $qr->qr_ID,
                ':group_id' => $groupPlayer
            ])
            ->one();

        if (isset($qrCheck->qr_check_ID)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Jou groep heeft deze QR al gescand.'));
            return $this->redirect(['site/overview-players']);
        }

        // Every thing is checked, now we can create the checked qr record.
        $model = new QrCheck;
        $model->qr_ID = $qr->qr_ID;
        $model->event_ID = $qr->event_ID;
        $model->group_ID = $groupPlayer;

        if ($model->save()) {
            Yii::$app->cache->flush();
            if ($model->qr->message != null) {
                Yii::$app->session->setFlash('info', $model->qr->message);
            }
            if ($model->qr->score < 0) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'QR code gecontroleerd! Maar je krijgt strafpunten...'));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'QR code gecontroleerd!'));
            }
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Kan QR code niet controleren!'));
        }
        return $this->redirect(['site/overview-players']);
    }

    /**
     * Updates an existing QrCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($qr_check_ID)
    {
        $model = $this->findModel($qr_check_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->flush();
            return $this->redirect(['view', 'id' => $model->qr_check_ID]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * @deprecated since Maart 2018
     * Deletes an existing QrCheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Finds the QrCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QrCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = QrCheck::findOne([
                'qr_check_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
