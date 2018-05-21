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
use app\models\NoodEnvelop;
use app\models\TimeTrail;
use app\models\TimeTrailCheck;
use app\models\TimeTrailItem;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\Event;
use app\models\Route;
use yii\web\NotFoundHttpException;
use app\models\CustomMap;

class MapController extends Controller
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
                    'You are not subscribed to any hike. If you organizing a hike you can start a new hike.
                    If you want to join an hike, look for a friend you is organising a hike and ask him to add your profile to the hike'
                )
            );
            return $this->redirect(['/users/view']);
        }
        
        switch (Yii::$app->user->identity->getStatusForEvent()) {
            case EventNames::STATUS_opstart:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie) {
                    return $this->redirect(['/map/edit']);
                }
                break;
            case EventNames::STATUS_introductie:
            case EventNames::STATUS_gestart:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie) {
                    return $this->redirect(['/map/view']);
                }
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_deelnemer) {
                    return $this->redirect(['/map/group-view']);
                }
                break;
            case EventNames::STATUS_beindigd:
                if (Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie ||
                    Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_deelnemer) {
                    return $this->redirect(['/map/view']);
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

        $screen = Yii::$app->getRequest()->getCookies()->getValue('screen_size') - 520;
        if ($screen === null ||
            $screen < 250) {
            $screen = 250;
        }
        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $map = new CustomMap([
            'center' => $coord,
            'zoom' => 14,
            'width' => '100%',
            'height' => $screen,
        ]);

        $map->setPostMarkers($routeModel->day_date, true);
        $map->setQrMarkers($routeModel->route_ID, true);
        $map->setHintMarkers($routeModel->route_ID, true);
        $map->setvragenMarkers($routeModel->route_ID, true);
        $map->setTimeTrailMarkers(true);

        // Lets add a marker now
        $marker = new Marker([
            'clickable' => true,
            'crossOnDrag' => true,
            'draggable' => true,
            'position' => $coord,
            'title' => 'New item',
        ]);

        if ($map->getMarkersCenterCoordinates() !== null) {
            $marker->setPosition($map->getMarkersCenterCoordinates());
        }
        // Provide a shared InfoWindow to the marker
        $marker->attachInfoWindow(
            new InfoWindow([
            'content' => '<p>Point for new item</p>'
            ])
        );

        $event = new Event([
            "trigger" => "drag",
            "js" =>
            "
                document.getElementById('latitude').innerHTML = event.latLng.lat().toFixed(6);
                document.getElementById('longitude').innerHTML = event.latLng.lng().toFixed(6);

                function setLngLat() {
                    x=document.getElementsByClassName('latitude');
                    for(var i = 0; i < x.length; i++){
                        x[i].value=event.latLng.lat();    // Change the content
                    }
                    y=document.getElementsByClassName('longitude');

                    for(var i = 0; i < y.length; i++){
                        y[i].value=event.latLng.lng();    // Change the content
                    }
                }
                setLngLat()
            "
        ]);

        $marker->addEvent($event);

        $postenModel->latitude = $marker->getLat();
        $postenModel->longitude = $marker->getLng();
        $vragenModel->latitude = $marker->getLat();
        $vragenModel->longitude = $marker->getLng();
        $qrModel->latitude = $marker->getLat();
        $qrModel->longitude = $marker->getLng();
        $hintModel->latitude = $marker->getLat();
        $hintModel->longitude = $marker->getLng();
        $timeTrailItemModel->latitude = $marker->getLat();
        $timeTrailItemModel->longitude = $marker->getLng();

        // Add marker to the map
        $map->addOverlay($marker);
        $map->center = $map->getMarkersCenterCoordinates();
//        dd($map->getMarkersFittingZoom());
        $map->zoom = 2 + $map->getMarkersFittingZoom();
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

        $screen = Yii::$app->getRequest()->getCookies()->getValue('screen_size') - 320;
        if ($screen === null ||
            $screen < 250) {
            $screen = 250;
        }

        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $map = new CustomMap([
            'center' => $coord,
            'zoom' => 14,
            'width' => '100%',
            'height' => $screen,
        ]);

        $map->setPostMarkers($routeModel->day_date, false, $group);
        $map->setQrMarkers($routeModel->route_ID, false, $group);
        $map->setHintMarkers($routeModel->route_ID, false, $group);
        $map->setvragenMarkers($routeModel->route_ID, false, $group);
        $map->setTimeTrailMarkers(false, $group);

        if ($map->getMarkersCenterCoordinates() !== null) {
            $map->center = $map->getMarkersCenterCoordinates();
        }
        $map->zoom = 2 + $map->getMarkersFittingZoom();
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

        $screen = Yii::$app->getRequest()->getCookies()->getValue('screen_size') - 320;
        if ($screen === null ||
            $screen < 250) {
            $screen = 250;
        }

        $map = new CustomMap([
            'center' => $coord,
            'zoom' => 14,
            'width' => '100%',
            'height' => $screen,
        ]);

        $map->setPostMarkers($routeModel->day_date, false);
        $map->setQrMarkers($routeModel->route_ID, false);
        $map->setHintMarkers($routeModel->route_ID, false);
        $map->setvragenMarkers($routeModel->route_ID, false);
        $map->setTimeTrailMarkers(false);

        if ($map->getMarkersCenterCoordinates() !== null) {
            $map->center = $map->getMarkersCenterCoordinates();
        }
        $map->zoom = 2 + $map->getMarkersFittingZoom();
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

        if ($route_id === null) {
            $model = Route::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID])
                ->orderBy([
                    'route_volgorde' => SORT_ASC,
                    'day_date' => SORT_ASC])
                ->one();
        } else {
            $model = Route::findOne([
                    'route_ID' => $route_id,
                    'event_ID' => Yii::$app->user->identity->selected_event_ID]);
        }

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
