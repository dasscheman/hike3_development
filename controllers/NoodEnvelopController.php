<?php

namespace app\controllers;

use Yii;
use app\models\NoodEnvelop;
use app\models\EventNames;
use app\models\NoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\OpenNoodEnvelop;

/**
 * NoodEnvelopController implements the CRUD actions for TblNoodEnvelop model.
 */
class NoodEnvelopController extends Controller {

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
                        'actions' => ['index'],
                        'roles' => ['deelnemer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieIntrodutie',  'organisatieOpstart'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => FALSE, // deny all users
                        'roles' => ['*'],
                    ],
                ]
            ],
        ];
    }

    /**
     * Lists all NoodEnvelop models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new NoodEnvelopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new NoodEnvelop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($route_ID) {
        $model = new NoodEnvelop();

        if (Yii::$app->request->post('NoodEnvelop') &&
            $model->load(Yii::$app->request->post())) {
            $model->setNewOrderForNoodEnvelop();

            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Saved new hint.'));
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
                '/nood-envelop/create',
                'model' => $model
        ]);
    }

    /**
     * Updates an existing NoodEnvelop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($nood_envelop_ID) {
        $model = $this->findModel($nood_envelop_ID);
        if (Yii::$app->request->post('update') == 'delete') {
            $exist = OpenNoodEnvelop::find()
                ->where('event_ID=:event_id and nood_envelop_ID=:nood_envelop_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':nood_envelop_id' => $model->nood_envelop_ID
                ])
                ->exists();
            if (!$exist) {
                $model->delete();
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted hint.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete hint, it is opened by at least one group.'));
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('NoodEnvelop') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes to hint.'));
                return $this->redirect(['route/index']);
            }
        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render([
                '/nood-envelop/update',
                'model' => $model
        ]);
    }

    public function actionMoveUpDown() {
        $event_id = $_GET['event_id'];
        $nood_envelop_id = $_GET['nood_envelop_id'];
        $nood_envelop_volgorde = $_GET['volgorde'];
        $up_down = $_GET['up_down'];
        $route_id = NoodEnvelop::getRouteIdOfEnvelop($_GET['nood_envelop_id']);

        $currentModel = NoodEnvelop::findByPk($nood_envelop_id);

        $criteria = new CDbCriteria;

        if ($up_down == 'up') {
            $criteria->condition = 'event_ID =:event_id AND route_ID=:route_id AND nood_envelop_volgorde <:order';
            $criteria->params = array(':event_id' => $event_id, ':route_id' => $route_id, ':order' => $nood_envelop_volgorde);
            $criteria->order = 'nood_envelop_volgorde DESC';
        }
        if ($up_down == 'down') {
            $criteria->condition = 'event_ID =:event_id AND route_ID=:route_id AND nood_envelop_volgorde >:order';
            $criteria->params = array(':event_id' => $event_id, ':route_id' => $route_id, ':order' => $nood_envelop_volgorde);
            $criteria->order = 'nood_envelop_volgorde ASC';
        }
        $criteria->limit = 1;
        $previousModel = NoodEnvelop::findAll($criteria);

        $tempCurrentVolgorde = $currentModel->nood_envelop_volgorde;
        $currentModel->nood_envelop_volgorde = $previousModel[0]->nood_envelop_volgorde;
        $previousModel[0]->nood_envelop_volgorde = $tempCurrentVolgorde;

        $currentModel->save();
        $previousModel[0]->save();

        if (Route::routeIdIntroduction($currentModel->route_ID)) {
            return $this->redirect(array('route/viewIntroductie',
                    "route_id" => $currentModel->route_ID,
                    "event_id" => $currentModel->event_ID,));
        } else {
            return $this->redirect(array(
                    'route/view',
                    "route_id" => $currentModel->route_ID,
                    "event_id" => $currentModel->event_ID,));
        }
    }

    /**
     * Finds the TblNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $model = NoodEnvelop::findOne([
                'nood_envelop_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
