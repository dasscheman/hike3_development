<?php

namespace app\controllers;

use Yii;
use app\models\NoodEnvelop;
use app\models\NoodEnvelopSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\OpenNoodEnvelop;
use app\models\EventNames;
use app\models\RouteSearch;
use yii\helpers\Json;

/**
 * NoodEnvelopController implements the CRUD actions for TblNoodEnvelop model.
 */
class NoodEnvelopController extends Controller
{
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
                        'actions' => ['index'],
                        'roles' => ['deelnemer', 'organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieIntrodutie',  'organisatieOpstart'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update', 'map-update', 'ajaxupdate', 'move-up-down'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => false, // deny all users
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
    public function actionIndex()
    {
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
    public function actionCreate($route_ID)
    {
        $model = new NoodEnvelop();

        d(Yii::$app->request->post());
        if (Yii::$app->request->post('NoodEnvelop') &&
            $model->load(Yii::$app->request->post())) {
            $model->setNewOrderForNoodEnvelop();

            if ($model->save()) {
                Yii::$app->session->setFlash('info', Yii::t('app', 'Nieuwe hint opgeslagen'));
                return $this->redirect(['map/index']);
            } else {
                foreach ($model->getErrors() as $error) {
                    Yii::$app->session->setFlash('error', Json::encode($error));
                }
            }
        } else {
            $model->route_ID = $route_ID;
            $model->event_ID = Yii::$app->user->identity->selected_event_ID;
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render(
                '/nood-envelop/create',
                ['model' => $model]
            );
    }

    /**
     * Without passing parameters this is used to determine what to do after a save.
     * When updating on the map page, the browser tab must be closed.
     *
     * @param type $nood_envelop_ID
     * @return type
     */
    public function actionMapUpdate($nood_envelop_ID)
    {
        return $this->actionUpdate($nood_envelop_ID, true);
    }

    /**
     * Updates an existing NoodEnvelop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($nood_envelop_ID, $map = null)
    {
        $model = $this->findModel($nood_envelop_ID);
        if (Yii::$app->request->post('update') == 'delete') {
            $exist = OpenNoodEnvelop::find()
                ->where('event_ID=:event_id and nood_envelop_ID=:nood_envelop_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':nood_envelop_id' => $model->nood_envelop_ID
                ]
                )
                ->exists();
            if (!$exist && Yii::$app->user->can('organisatieOpstart')) {
                $model->delete();
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Hint verwijderd.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Je kunt de hint niet verwijderen, er is een groep die hem al geopend heeft'));
            }
            if ($map === true) {
                echo "<script>window.close(); window.opener.location.reload(true);</script>";
                return;
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('NoodEnvelop') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->cache->flush();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Wijzigingen opgeslagen.'));
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
                '/nood-envelop/update',
                ['model' => $model]
        );
    }

    public function actionMoveUpDown()
    {
        $model = $this->findModel(Yii::$app->request->get('nood_envelop_id'));
        $up_down = Yii::$app->request->get('up_down');

        if ($up_down === 'up') {
            $previousModel = NoodEnvelop::find()
                ->where('event_ID =:event_id and route_ID =:route_ID and nood_envelop_volgorde <:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_ID' => $model->route_ID, ':order' => $model->nood_envelop_volgorde])
                ->orderBy('nood_envelop_volgorde DESC')
                ->one();
        } elseif ($up_down === 'down') {
            $previousModel = NoodEnvelop::find()
                ->where('event_ID =:event_id AND route_ID =:route_ID AND nood_envelop_volgorde >:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_ID' => $model->route_ID, ':order' => $model->nood_envelop_volgorde])
                ->orderBy('nood_envelop_volgorde ASC')
                ->one();
        }

        // Dit is voor als er een reload wordt gedaan en er is geen previousModel.
        // Opdeze manier wordt er dan voorkomen dat er een fatal error komt.
        if (isset($previousModel)) {
            $tempCurrentVolgorde = $model->nood_envelop_volgorde;
            $model->nood_envelop_volgorde = $previousModel->nood_envelop_volgorde;
            $previousModel->nood_envelop_volgorde = $tempCurrentVolgorde;

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
     * Finds the TblNoodEnvelop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblNoodEnvelop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
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
