<?php

namespace app\controllers;

use Yii;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\NoodEnvelop;
use app\models\OpenNoodEnvelop;
use app\models\OpenNoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OpenNoodEnvelopController implements the CRUD actions for TblOpenNoodEnvelop model.
 */
class OpenNoodEnvelopController extends Controller {

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
                        'actions' => ['open'],
                        'roles' => ['deelnemerIntroductie', 'deelnemerGestartTime'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'update'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ]
        ];
    }
    public function beforeAction($action) {
        if($action->id == 'open'){
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    /**
     * Lists all TblOpenNoodEnvelop models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new OpenNoodEnvelopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $event_id = Yii::$app->user->identity->selected_event_ID;
        $eventNames = new EventNames();
        $startDate = $eventNames->getStartDate($event_id);
        $endDate = $eventNames->getEndDate($event_id);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'startDate' => $startDate,
                'endDate' => $endDate
        ]);
    }

    /**
     * Creates a new OpenNoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionOpen($nood_envelop_ID) {
        Yii::$app->cache->flush();        
        $now = date('Y-m-d H:i:s');

        $model = new OpenNoodEnvelop;
        $modelEnvelop = NoodEnvelop::findOne($nood_envelop_ID);
        if(!$modelEnvelop) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Geen geldige hint.'));
            return $this->redirect(['site/overview-players']);
        }

        if($modelEnvelop->event_ID != Yii::$app->user->identity->selected_event_ID) {
            $modelDeelnemersEvent = DeelnemersEvent::find()
                ->where([
                    'event_ID' => $modelEnvelop->event_ID,
                    'user_ID' => Yii::$app->user->identity->id
                ])
                ->one();

            if (isset($modelDeelnemersEvent->rol) &&
                $modelDeelnemersEvent->rol = 3) {
                  Yii::$app->session->setFlash('error', Yii::t('app', 'Deze hint is niet voor deze hike.'));
                  return $this->redirect(['site/overview-players']);
            }
            Yii::$app->user->identity->selected_event_ID = $modelDeelnemersEvent->event_ID;
            Yii::$app->user->identity->save();
            Yii::$app->cache->flush();
        }

        $deelnemersEvent = new DeelnemersEvent();
        $groupPlayer = $deelnemersEvent->getGroupOfPlayer($modelEnvelop->event_ID);
        if (!$groupPlayer) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Je mag deze hint niet openen.'));
            return $this->redirect(['site/index']);
        }

        if (isset($modelEnvelop->route->start_datetime) && $modelEnvelop->route->start_datetime > $now) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze hint hoort bij routeonderdeel die nog niet gestart is.'));
            return $this->redirect(['site/overview-players']);
        }

        if (isset($modelEnvelop->route->end_datetime) && $modelEnvelop->route->end_datetime < $now) {
          Yii::$app->session->setFlash('error', Yii::t('app', 'Deze hint hoort bij routeonderdeel die al afegelopen is..'));
          return $this->redirect(['site/overview-players']);
        }

        $openHint = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND nood_envelop_ID =:nood_envelop_id AND group_ID =:group_id')
            ->params([
                ':event_id' => $modelEnvelop->event_ID,
                ':nood_envelop_id' => $modelEnvelop->nood_envelop_ID,
                ':group_id' => $groupPlayer
            ])
            ->one();

        if (isset($openHint->open_nood_envelop_ID)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Jou groep heeft deze Hint al geopend.'));
            return $this->redirect(['site/overview-players']);
        }

        if (!Yii::$app->request->post()) {
            return $this->renderPartial('open', [
                    'model' => $model,
                    'modelEnvelop' => $modelEnvelop,
            ]);
        }

        if (Yii::$app->request->post('open-hint') == 'open-hint') {
            if (!$model->load(Yii::$app->request->post())) {
                $model->event_ID = Yii::$app->request->post('event_ID');
                $model->nood_envelop_ID = Yii::$app->request->post('nood_envelop_ID');
            }
            $model->group_ID = $groupPlayer;
            $model->opened = 1;
            if (!$model->save()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je kunt deze hint niet openen'));
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Hint is geopend.'));
                Yii::$app->session->setFlash('info', Yii::t('app', 'Alle geopende hints worden op dit dashboard getoond.'));
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
    public function actionUpdate($open_nood_envelop_ID) {
        $model = $this->findModel($open_nood_envelop_ID);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'open_nood_envelop_ID' => $model->open_nood_envelop_ID]);
        } else {
            Yii::$app->cache->flush();
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Finds the TblOpenNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblOpenNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = OpenNoodEnvelop::findOne([
                'open_nood_envelop_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
