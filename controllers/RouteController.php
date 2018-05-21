<?php

namespace app\controllers;

use Yii;
use app\models\EventNames;
use app\models\NoodEnvelop;
use app\models\OpenVragen;
use app\models\Qr;
use app\models\Route;
use app\models\RouteSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RouteController implements the CRUD actions for Route model.
 */
class RouteController extends Controller
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
                        'actions' => ['index', 'move-up-down', 'update'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['organisatieOpstart'],
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
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $event_Id = Yii::$app->user->identity->selected_event_ID;
        $startDate = EventNames::getStartDate($event_Id);
        $endDate = EventNames::getEndDate($event_Id);

        $searchModel = new RouteSearch();

        $this::setRouteIndexMessage($event_Id);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'startDate' => $startDate,
                'endDate' => $endDate
        ]);
    }

    /**
     * Displays a single Route model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $route_id = $_GET['route_id'];
        $event_id = $_GET['event_id'];

        $where = "event_ID = $event_id AND route_ID =$route_id";

        $vragenDataProvider = new CActiveDataProvider('OpenVragen', array(
            'criteria' => array(
                'condition' => $where,
                'order' => 'vraag_volgorde ASC',
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $envelopDataProvider = new CActiveDataProvider('NoodEnvelop', array(
            'criteria' => array(
                'condition' => $where,
                'order' => 'nood_envelop_volgorde ASC',
            ),
            'pagination' => array(
                'pageSize' => 50,
            ),
        ));

        $qrDataProvider = new CActiveDataProvider('Qr', array(
            'criteria' => array(
                'condition' => $where,
                'order' => 'qr_volgorde ASC',
            ),
            'pagination' => array(
                'pageSize' => 15,
            ),
        ));
        return $this->render('view', [
                'model' => $this->findModel($id),
                'vragenDataProvider' => $vragenDataProvider,
                'envelopDataProvider' => $envelopDataProvider,
                'qrDataProvider' => $qrDataProvider,
        ]);
    }

    /**
     * Creates a new Route model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date = null)
    {
        $model = new Route;

        if (Yii::$app->request->post('Route') &&
            $model->load(Yii::$app->request->post())) {
            $model->setRouteOrder();
            if ($model->save()) {
                return $this->redirect(['/route/index']);
            }
        } else {
            $model->setAttributes([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
            ]);
            // This set the tab from which the call is started.
            if ($date != null) {
                $model->setAttributes(['day_date' => $date]);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', ['model' => $model]);
        }

        return $this->render([
                '/route/create',
                'model' => $model
        ]);
    }

    /**
     * Updates an existing Route model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($route_ID)
    {
        $model = $this->findModel($route_ID);
        if (Yii::$app->request->post('update') == 'delete') {
            $exist = Qr::find()
                ->where('event_ID=:event_id and route_ID=:route_id')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':route_id' => $model->route_ID
                ]
                )
                ->exists();

            if (!$exist) {
                $exist = OpenVragen::find()
                    ->where('event_ID=:event_id and route_ID=:route_id')
                    ->addParams(
                        [
                            ':event_id' => Yii::$app->user->identity->selected_event_ID,
                            ':route_id' => $model->route_ID
                    ]
                    )
                    ->exists();
            }

            if (!$exist) {
                $exist = NoodEnvelop::find()
                    ->where('event_ID=:event_id and route_ID=:route_id')
                    ->addParams(
                        [
                            ':event_id' => Yii::$app->user->identity->selected_event_ID,
                            ':route_id' => $model->route_ID
                    ]
                    )
                    ->exists();
            }

            if (!$exist && Yii::$app->user->can('organisatieOpstart')) {
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted route.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Could not delete route, it contains items which should be removed first.'));
            }
            return $this->redirect(['route/index']);
        }

        if (Yii::$app->request->post('Route') &&
            $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Saved changes to route.'));
                return $this->redirect(['route/index']);
            }
        }

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', ['model' => $model]);
        }

        return $this->render([
                '/route/update',
                'model' => $model
        ]);
    }

    /*
     * Deze actie wordt gebruikt voor de grid velden. 
     */

    public function actionMoveUpDown($route_ID, $up_down)
    {
        $model = $this->findModel($route_ID);
        if ($up_down === 'up') {
            $previousModel = Route::find()
                ->where('event_ID =:event_id AND (ISNULL(day_date) OR day_date =:date) and route_volgorde <:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $model->day_date, ':order' => $model->route_volgorde])
                ->orderBy('route_volgorde DESC')
                ->one();
        } elseif ($up_down === 'down') {
            $previousModel = Route::find()
                ->where('event_ID =:event_id AND (ISNULL(day_date) OR day_date =:date) AND route_volgorde >:order')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $model->day_date, ':order' => $model->route_volgorde])
                ->orderBy('route_volgorde ASC')
                ->one();
        }

        // Dit is voor als er een reload wordt gedaan en er is geen previousModel.
        // Opdeze manier wordt er dan voorkomen dat er een fatal error komt.
        if (isset($previousModel)) {
            $tempCurrentVolgorde = $model->route_volgorde;
            $model->route_volgorde = $previousModel->route_volgorde;
            $previousModel->route_volgorde = $tempCurrentVolgorde;

            if ($model->validate() &&
                $previousModel->validate()) {
                $model->save();
                $previousModel->save();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Cannot change order.'));
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

    /**
     * Finds the Route model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Route the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Route::findOne([
                'route_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function setRouteIndexMessage($event_id)
    {
        $route = Route::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id]);

        if ($route->count() < 3) {
            Yii::$app->session->setFlash(
                'route',
                Yii::t(
                    'app',
                    'Here you can create route items for each day.
                   The route item \'Introduction\' you can use before the start of the hike, so players can get familiar with hike-app.nl.
                   For each route item you can create questions, hints or silent stations.'
                )
            );
        }
        $questionModel = new OpenVragen;
        $questions = $questionModel->find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id]);

        if ($questions->count() < 3) {
            Yii::$app->session->setFlash(
                'question',
                Yii::t(
                    'app',
                    'Questions are visable by player only when the hike is started and the same day is selected.
                   The field {awnser} is never visable by players.',
                    ['awnser' => $questionModel->getAttributeLabel('goede_antwoord'),]
                )
            );
        }

        $hintsModel = new NoodEnvelop;
        $hints = $hintsModel->find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id]);

        if ($hints->count() < 3) {
            Yii::$app->session->setFlash(
                'hint',
                Yii::t(
                    'app',
                    'Hints are visable by player only when the hike is started and the same day is selected.
                   The field {remark} and {cordinate} are only visable by players when whey open a hint.
                   The score fields are penalty score, use positive interger numbers.
                   Use the {name} to give a clear description what this hint is about',
                    [
                    'remark' => $hintsModel->getAttributeLabel('opmerkingen'),
                    'cordinate' => $hintsModel->getAttributeLabel('coordinaat'),
                    'name' => $hintsModel->getAttributeLabel('nood_envelop_name'),
                ]
                )
            );
        }

        $qrModel = new Qr;
        $qr = $qrModel->find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => $event_id]);

        if ($qr->count() <= $route->count()) {
            Yii::$app->session->setFlash(
                'qr',
                Yii::t(
                    'app',
                    'Silent station have to be printed and hanged along the hike route.
                   Players get points when they scan the QR code.
                   A silent station is automaticly created for each route item'
                )
            );
        }
    }
}
