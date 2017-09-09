<?php

namespace app\controllers;

use Yii;
use app\models\TimeTrailCheck;
use app\models\TimeTrailCheckSearch;
use app\models\TimeTrailItem;
use app\models\DeelnemersEvent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * TimeTrailCheckController implements the CRUD actions for TimeTrailCheck model.
 */
class TimeTrailCheckController extends Controller
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
     * Lists all TimeTrailCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TimeTrailCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $code = Yii::$app->request->get('code');
        $groupPlayer = DeelnemersEvent::getGroupOfPlayer();

        if(!$groupPlayer){
            Yii::$app->session->setFlash('error', Yii::t('app', 'Your are not a member of a group in this event.'));
            return $this->redirect(['site/index']);
        }

        $timeTrailItem = TimeTrailItem::find()
                ->where('event_ID =:event_id AND code =:code')
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':code'  => $code])
                ->one();

        if (!isset($timeTrailItem->code)){
            Yii::$app->session->setFlash('error', Yii::t('app', 'Not a valid Time Trail code.'));
            return $this->redirect(['site/overview-players']);
        }

        $timeTrailCheck = $timeTrailItem->getTimeTrailItemCheckedByGroupCurrentUser();

        if ($timeTrailCheck != NULL){
            Yii::$app->session->setFlash('error', Yii::t('app', 'Your group already scanned this time trail code.'));
            return $this->redirect(['time-trail/status']);
        }
        // Er is nog geen check voor huidig item, daarom overschrijven we hier de variable
        $timeTrailCheck = new TimeTrailCheck;

        // Get the previous items
        $timeTrailItemPrevious = $timeTrailItem->getPreviousItem();

        if($timeTrailItemPrevious != NULL) {
            // Er is een vorig item, dat we moeten controleren en checken.
            $timeTrailCheckPrevious = $timeTrailItemPrevious->getTimeTrailItemCheckedByGroupCurrentUser();

            if ($timeTrailCheckPrevious == NULL) {
                // Er is een vorig item aanwezig, dat niet gechecked is...
                Yii::$app->session->setFlash('error', Yii::t('app', 'It seems you missed a time trail point.'));
                return $this->redirect(['site/overview-players']);
            }

            if($timeTrailCheckPrevious->start_time == NULL) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Something went wrong!!.'));
                return $this->redirect(['site/overview-players']);
            }

            // Er is een vorig item dat al gechecked is. Nu moet de eindtijd gezet worden
            // en bepaald of de groep succes heeft.
            $end_date = strtotime($timeTrailCheckPrevious->start_time) + (strtotime($timeTrailCheckPrevious->timeTrailItem->max_time)  - strtotime('TODAY'));
            $timeTrailCheckPrevious->end_time = \Yii::$app->setupdatetime->storeFormat(time(), 'datetime');

            if ($end_date>time()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'You made it'));
                $timeTrailCheckPrevious->succeded = 1;
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'You are to late'));
                $timeTrailCheckPrevious->succeded = 0;
            }

            if (!$timeTrailCheckPrevious->validate()){
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

        if (!$timeTrailCheck->save()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Something went wrong with saving!!'));
            foreach ($timeTrailCheck->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
            return $this->redirect(['site/overview-players']);
        }

        // Als er een vorig item is, dan moet vorig check nog opgeslagen worden.
        if($timeTrailItemPrevious != NULL) {
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
    public function actionUpdate($id)
    {
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
    public function actionDelete($id)
    {
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
    protected function findModel($id)
    {
        if (($model = TimeTrailCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
