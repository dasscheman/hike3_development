<?php

namespace app\controllers;

use Yii;
use app\models\OpenMap;
use app\models\RouteTrack;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * RouteTrackController implements the CRUD actions for RouteTrack model.
 */
class RouteTrackController extends Controller
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
                        'actions' => [ 'upload-track', 'index',
                            'edit-track', 'delete', 'ajax-delete',
                            'delete-track', 'delete-waypoints'],
                        'roles' => ['organisatie'],
                    ],
                ]
            ]
        ];
    }

    public function actionEditTrack()
    {
        $map = new OpenMap([
            'zoom' => 12,
            'clientOptions' => [
                'fullscreenControl' => true
            ]
        ]);

        $map->setEventWayPoints();
        $tracks = RouteTrack::find()
            ->where(
            [
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'type' => RouteTrack::TYPE_track
            ])
            ->select('name')
            ->distinct()
            ->all();

        $wp_exists = RouteTrack::find()
            ->where(
            [
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'type' => RouteTrack::TYPE_waypoint
            ])
            ->exists();

        // Different layers can be added to our map using the `addLayer` function.
        $map->setEventTrackPoints();
        // dd(empty($map->allCoordinatesArray));
        if(!empty($map->allCoordinatesArray)){
            $map->clientOptions['bounds'] =json_encode($map->allCoordinatesArray);
        }

        return $this->render('update', [
            'wp_exists' => $wp_exists,
            'tracks' => $tracks,
            'map' => $map
        ]);
    }

    public function actionUploadTrack()
    {
        $model = new RouteTrack;

        if ($model->load(Yii::$app->request->post())) {
            // get the uploaded file instance. for multiple file uploads
            // the following data will return an array
            $file = UploadedFile::getInstance($model, 'track_temp');
            $gpx = simplexml_load_file($file->tempName);
            if (isset($gpx->trk)) {
                $model->importTrack($gpx->trk, $file->name);
            }
            if (isset($gpx->wpt)) {
                $model->importPoints($gpx->wpt, RouteTrack::TYPE_waypoint);
            }
            if (isset($gpx->rte->rtept)) {
                $model->importPoints($gpx->rte->rtept, RouteTrack::TYPE_route);
            }
        }
        return $this->redirect(['site/overview-organisation']);
    }

    /**
     * Lists all RouteTrack models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RouteTrack::find()
                ->where(['type' => RouteTrack::TYPE_waypoint])
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RouteTrack model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RouteTrack model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RouteTrack();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->route_track_ID]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RouteTrack model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->route_track_ID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RouteTrack model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionAjaxDelete()
    {
        $model = $this->findModel(Yii::$app->request->post('id'));

        if ($model->delete()) {
            return true;
        } else {
            foreach ($model->getErrors() as $error) {
                return Json::encode($error);
            }
        }
    }

    public function actionDeleteTrack()
    {
        $track_name = Yii::$app->request->post('track_name');
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $result = RouteTrack::deleteAll(
                'event_ID = :event_id AND type = :type AND name = :name',
                [
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':type' => RouteTrack::TYPE_track,
                    ':name' => $track_name
                ]);
            $dbTransaction->commit();
            if($result > 0) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Verwijderde track: ' .  $track_name));
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Kan track niet verwijderen: ' .  $e));
        }

        return $this->redirect(['edit-track']);
    }

    public function actionDeleteWaypoints()
    {
        $dbTransaction = Yii::$app->db->beginTransaction();
        try {
            $result = RouteTrack::deleteAll(
                'event_ID = :event_id AND type = :type',
                [
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':type' => RouteTrack::TYPE_waypoint
                ]);
            $dbTransaction->commit();
            if($result > 0) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Aantal verwijderde waypoints: ' .  $result));
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Kan waypoints niet verwijderen: ' .  $e));
        }

        return $this->redirect(['edit-track']);
    }

    /**
     * Finds the RouteTrack model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RouteTrack the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RouteTrack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
