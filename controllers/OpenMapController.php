<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Qr;
use app\models\RouteSearch;
use app\models\Posten;
use app\models\OpenVragen;
use app\models\OpenMap;
use app\models\NoodEnvelop;
use app\models\TimeTrail;
use app\models\TimeTrailItem;
use app\models\Route;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\controls\Control;

use dosamigos\leaflet\layers\Layer;
use yii\web\NotFoundHttpException;

class OpenMapController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['organisatie', 'deelnemer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['group-view'],
                        'roles' => ['deelnemer'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['organisatie',  'deelnemerEnded'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['organisatie'],
                    ],
                    [
                        'allow' => false, // deny all users
                        'roles' => ['*'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isguest) {
            return $this->render('/site/index');
        }
        if (!Yii::$app->user->identity->getDeelnemersEventsByUserID()->exists()) {
            Yii::$app->session->setFlash(
                'warning',
                Yii::t(
                    'app',
                    'Je bent niet ingeeschreven voor een hike. Als je er een organiseerd, maak er dan een hike aan.
                    Als je mee wilt doen aan een hike zoek dan een vriend die een hike organiseerd en vraag hem jou profiel aan de hike toe tevoegen.'
                )
            );
            return $this->redirect(['/users/view']);
        }

        switch (Yii::$app->user->identity->getStatusForEvent()) {
            case EventNames::STATUS_opstart:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie) {
                    return $this->redirect(['/open-map/edit']);
                }
                break;
            case EventNames::STATUS_introductie:
            case EventNames::STATUS_gestart:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie) {
                    return $this->redirect(['/open-map/view']);
                }
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_deelnemer) {
                    return $this->redirect(['/open-map/group-view']);
                }
                break;
            case EventNames::STATUS_beindigd:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie ||
                    Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_deelnemer) {
                    return $this->redirect(['/open-map/view']);
                }
                break;
            case EventNames::STATUS_geannuleerd:
            default:
                return $this->redirect(['/site/index']);
        }

        return $this->redirect(['/site/index']);
    }

    public function actionEdit()
    {
        $event_id = Yii::$app->user->identity->selected_event_ID;
        $routeModel = $this->findRouteModel(Yii::$app->request->get('route_ID'));
        $postenModel = new Posten([
            'event_ID' => $event_id,
            'date' => $routeModel->day_date
        ]);
        $vragenModel = new OpenVragen([
            'event_ID' => $event_id,
            'route_ID' => $routeModel->route_ID
        ]);
        $qrModel = new Qr([
            'event_ID' => $event_id,
            'route_ID' => $routeModel->route_ID
        ]);
        $hintModel = new NoodEnvelop([
            'event_ID' => $event_id,
            'route_ID' => $routeModel->route_ID
        ]);
        $timeTrailModel = new TimeTrail([
            'event_ID' => $event_id
        ]);
        $timeTrailItemModel = new TimeTrailItem([
            'event_ID' => $event_id
        ]);

        $timeTrailData = TimeTrail::find()
            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
            ->all();

        $routeSearchModel = new RouteSearch();
        $routeDataProvider = $routeSearchModel->searchRouteInEvent(Yii::$app->request->queryParams);
        $map = new OpenMap([
            'zoom' => 12,
            'clientOptions' => [
                'fullscreenControl' => true
            ]
        ]);

        // Different layers can be added to our map using the `addLayer` function.
        $map->setPostMarkers($routeModel->day_date, true);
        $map->setQrMarkers($routeModel->route_ID, true);
        $map->setHintMarkers($routeModel->route_ID, true);
        $map->setVragenMarkers($routeModel->route_ID, true);
        $map->setTimeTrailMarkers(true);
        $map->setEventTrack();
        $map->setEventWayPoints();

        $marker = $map->getDragableMarker();      // add the marker
        $map->addLayer($marker);      // add the marker
        $map->clientOptions['bounds'] =json_encode($map->allCoordinatesArray);

        return $this->render('index', [
                'routeModel' => $routeModel,
                'postenModel' => $postenModel,
                'qrModel' => $qrModel,
                'vragenModel' => $vragenModel,
                'hintModel' => $hintModel,
                'timeTrailModel' => $timeTrailModel,
                'timeTrailItemModel' => $timeTrailItemModel,
                'timeTrailData' => $timeTrailData,
                'routeSearchModel' => $routeSearchModel,
                'routeDataProvider' => $routeDataProvider,
                'marker' => $marker,
                'map' => $map
        ]);
    }

    public function actionGroupView()
    {
        $group = Yii::$app->user->identity->getGroupUserForEvent();
        $routeModel = $this->findRouteModel(Yii::$app->request->get('route_ID'));
        $routeSearchModel = new RouteSearch();
        $routeDataProvider = $routeSearchModel->searchRouteInEvent(Yii::$app->request->queryParams);

        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $map = new OpenMap([
            'center' => $coord,
            'zoom' => 12,
            'clientOptions' => [
                'fullscreenControl' => true
            ]
        ]);

        $map->setPostMarkers($routeModel->day_date, false, $group);
        $map->setQrMarkers($routeModel->route_ID, false, $group);
        $map->setHintMarkers($routeModel->route_ID, false, $group);
        $map->setVragenMarkers($routeModel->route_ID, false, $group);
        $map->setTimeTrailMarkers(false, $group);
        $map->clientOptions['bounds'] =json_encode($map->allCoordinates);

        return $this->render('view', [
                'routeModel' => $routeModel,
                'routeSearchModel' => $routeSearchModel,
                'routeDataProvider' => $routeDataProvider,
                'map' => $map
        ]);
    }

    public function actionView()
    {
        $routeModel = $this->findRouteModel(Yii::$app->request->get('route_ID'));
        $timeTrailData = TimeTrail::find()
            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
            ->all();

        $routeSearchModel = new RouteSearch();
        $routeDataProvider = $routeSearchModel->searchRouteInEvent(Yii::$app->request->queryParams);
        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);

        $map = new OpenMap([
            'center' => $coord,
            'zoom' => 14,
        ]);

        $map->setPostMarkers($routeModel->day_date, false);
        $map->setQrMarkers($routeModel->route_ID, false);
        $map->setHintMarkers($routeModel->route_ID, false);
        $map->setVragenMarkers($routeModel->route_ID, false);
        $map->setTimeTrailMarkers(false);
        $map->clientOptions['bounds'] =json_encode($map->allCoordinates);

        return $this->render('view', [
                'routeModel' => $routeModel,
                'timeTrailData' => $timeTrailData,
                'routeSearchModel' => $routeSearchModel,
                'routeDataProvider' => $routeDataProvider,
                'map' => $map
        ]);
    }

    /**
     * Finds the Route model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Route the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findRouteModel($id = null)
    {
        $route_id = null;
        if ($id !== null) {
            $route_id = $id;
        } elseif (Yii::$app->getRequest()->getCookies()->getValue('route_map_tab' . Yii::$app->user->id) !== null) {
            $route_id = Yii::$app->getRequest()->getCookies()->getValue('route_map_tab' . Yii::$app->user->id);
        }

        if ($route_id !== null) {
            $model = Route::find()
                ->where([
                    'route_ID' => $route_id,
                    'event_ID' => Yii::$app->user->identity->selected_event_ID]);
        }

        if ($route_id === null || !$model->exists()) {
            $model = Route::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID])
                ->orderBy([
                    'route_volgorde' => SORT_ASC,
                    'day_date' => SORT_ASC]);
        }

        if ($model->one() !== null) {
            return $model->one();
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
