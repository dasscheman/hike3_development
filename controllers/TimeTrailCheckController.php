<?php

namespace app\controllers;

use Yii;
use app\models\TimeTrailCheck;
use app\models\TimeTrailCheckSearch;
use app\models\TimeTrailItem;
use app\models\DeelnemersEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TimeTrailCheckController implements the CRUD actions for TimeTrailCheck model.
 */
class TimeTrailCheckController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
                        'allow' => FALSE,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['deelnemerGestartTime'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['organisatieIntroductie', 'organisatieOpstart'],
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
     * Lists all TimeTrailCheck models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TimeTrailCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TimeTrailCheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TimeTrailCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $code = Yii::$app->request->get('code');
        $timeTrailItem = TimeTrailItem::find()
            ->where('code =:code')
            ->params([
                ':code' => $code])
            ->one();

        if (!isset($timeTrailItem->code)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Geen geldige tijdrit code.'));
            return $this->redirect(['site/overview-players']);
        }

        if($timeTrailItem->event_ID != Yii::$app->user->identity->selected_event_ID) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Deze tijdrit is niet voor deze hike.'));
            return $this->redirect(['site/overview-players']);
        }

        $deelnemersEvent = new DeelnemersEvent();
        $groupPlayer = $deelnemersEvent->getGroupOfPlayer($timeTrailItem->event_ID);
        if (!$groupPlayer) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Geen geldige tijdrit code.'));
            return $this->redirect(['site/index']);
        }

        $timeTrailCheck = $timeTrailItem->getTimeTrailItemCheckedByGroupCurrentUser();

        if ($timeTrailCheck != NULL) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Jou groep heeft deze QR al gescand.'));
            return $this->redirect(['time-trail/status']);
        }
        // Er is nog geen check voor huidig item, daarom overschrijven we hier de variable
        $timeTrailCheck = new TimeTrailCheck;

        // Get the previous items
        $timeTrailItemPrevious = $timeTrailItem->getPreviousItem();

        if ($timeTrailItemPrevious != NULL) {
            // Er is een vorig item, dat we moeten controleren en checken.
            $timeTrailCheckPrevious = $timeTrailItemPrevious->getTimeTrailItemCheckedByGroupCurrentUser();

            if ($timeTrailCheckPrevious == NULL) {
                // Er is een vorig item aanwezig, dat niet gechecked is...
                Yii::$app->session->setFlash('error', Yii::t('app', 'Het lijkt erop dat je een tijdritpunt gemist.'));
                return $this->redirect(['site/overview-players']);
            }

            if ($timeTrailCheckPrevious->start_time == NULL) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Er is iets niet goed gegaan!!.'));
                return $this->redirect(['site/overview-players']);
            }

            // Er is een vorig item dat al gechecked is. Nu moet de eindtijd gezet worden
            // en bepaald of de groep succes heeft.
            $end_date = strtotime($timeTrailCheckPrevious->start_time) + (strtotime($timeTrailCheckPrevious->timeTrailItem->max_time) - strtotime('TODAY'));
            $timeTrailCheckPrevious->end_time = \Yii::$app->setupdatetime->storeFormat(time(), 'datetime');

            if ($end_date > time()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je hebt het gehaald'));
                $timeTrailCheckPrevious->succeded = 1;
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je bent te laat'));
                $timeTrailCheckPrevious->succeded = 0;
            }

            if (!$timeTrailCheckPrevious->validate()) {
                // Hier hebben we de vorige check gevalideerd. En in het geval er iets niet
                // goed is zetten we errors en gaan terug naar het spelers overzicht.
                Yii::$app->session->setFlash('error', Yii::t('app', 'Something went wrong with saving!!.'));
                foreach ($timeTrailCheckPrevious->getErrors() as $error) {
                    Yii::$app->session->setFlash('error', Json::encode($error));
                }
                return $this->redirect(['site/overview-players']);
            }
        }

        $timeTrailCheck->time_trail_item_ID = $timeTrailItem->time_trail_item_ID;
        $timeTrailCheck->event_ID = $timeTrailItem->event_ID;
        $timeTrailCheck->group_ID = $groupPlayer;
        $timeTrailCheck->start_time = \Yii::$app->setupdatetime->storeFormat(time(), 'datetime');

        if (!$timeTrailCheck->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Something went wrong with saving!!'));
            foreach ($timeTrailCheck->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
            return $this->redirect(['site/overview-players']);
        }

        // Als er een vorig item is, dan moet vorig check nog opgeslagen worden.
        if ($timeTrailItemPrevious != NULL) {
            // Deze hadden we al gevalideerd, dus dat zal nog wel goed zijn.
            $timeTrailCheckPrevious->save(FALSE);
        }

        Yii::$app->cache->flush();
        return $this->redirect(['time-trail/status']);
    }

    /**
     * Updates an existing TimeTrailCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->cache->flush();
            return $this->redirect(['view', 'id' => $model->time_trail_check_ID]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TimeTrailCheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TimeTrailCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TimeTrailCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = TimeTrailCheck::findOne([
                'time_trail_check_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
