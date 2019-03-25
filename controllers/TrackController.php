<?php

namespace app\controllers;

use Yii;
use app\models\Track;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TrackController implements the CRUD actions for Track model.
 */
class TrackController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'delete', 'status', 'switch'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Track models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Track::find()
                ->where('user_ID =:user_id')
                ->params([':user_id' => Yii::$app->user->id])
                ->groupBy(['event_ID']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Track model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (empty(Yii::$app->request->post('trackdata'))) {
            return;
        }

        if (!Yii::$app->user->identity->allow_track) {
            return Json::encode(false);
        }

        if (Yii::$app->user->identity->getStatusForEvent() !== EventNames::STATUS_gestart &&
            Yii::$app->user->identity->getStatusForEvent() !== EventNames::STATUS_introductie) {
            return Json::encode(false);
        }

        $trackData = json_decode(Yii::$app->request->post('trackdata'), true);
        $array = json_decode($trackData, true);
        foreach ($array as $item) {
            $model = new Track();
            $model->latitude = $item['latitude'];
            $model->longitude = $item['longitude'];
            $model->accuracy = $item['accuracy'];
            $model->timestamp = (int) $item['timestamp'];
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
            $model->user_ID = Yii::$app->user->identity->id;
            if (DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID)) {
                $model->group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID);
            }
            if (!$model->save()) {
                return Json::encode($model->getErrors());
            }
        }
        if ($model->group_ID === null) {
            TagDependency::invalidate(Yii::$app->cache, 'tracks_user_' . $model->user_ID);
        } else {
            TagDependency::invalidate(Yii::$app->cache, 'tracks_group_' . $model->group_ID);
        }
        return;
    }

    /**
     * Deletes an existing Track model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($event_id)
    {
        Track::deleteAll(
            'event_ID =:event_id AND user_ID =:user_id',
            [
                ':event_id' => $event_id,
                ':user_id' => Yii::$app->user->id
            ]
        );
        return $this->redirect(['index']);
    }

    public function actionStatus()
    {
        if (!Yii::$app->user->identity->allow_track) {
            return $this->asJson(['status' => 'false']);
        }

        if (Yii::$app->user->identity->getStatusForEvent() !== EventNames::STATUS_gestart) {
            return $this->asJson(['status' => 'false']);
        }
        $track = new Track;
        if (!$track->checkInterval()) {
            return $this->asJson(['status' => 'false']);
        }
        return $this->asJson(['status' => 'true']);
    }

    public function actionSwitch()
    {
        Yii::$app->user->identity->allow_track = !Yii::$app->user->identity->allow_track;
        Yii::$app->user->identity->save();
    }

    /**
     * Finds the Track model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Track the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Track::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'Computer says no!'));
    }
}
