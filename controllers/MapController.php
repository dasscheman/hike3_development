<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Qr;
use app\models\RouteSearch;
use app\models\Posten;
use app\models\OpenVragen;
use app\models\NoodEnvelop;
use app\models\TimeTrail;
use app\models\TimeTrailItem;
use yii\web\Cookie;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\Event;
use dosamigos\google\maps\overlays\Icon;
use app\models\Route;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
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

        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $map = new Map([
            'center' => $coord,
            'zoom' => 14,
            'width' => '100%',
        ]);

        $this->setPostMarkers($map, $routeModel->day_date);
        $this->setQrMarkers($map, $routeModel->route_ID);
        $this->setHintMarkers($map, $routeModel->route_ID);
        $this->setvragenMarkers($map, $routeModel->route_ID);
        $this->setTimeTrailMarkers($map);




        // Lets add a marker now
        $marker = new Marker([
            'clickable' => true,
            'crossOnDrag' => true,
            'draggable' => true,
            'position' => $coord,
            'title' => 'New item',
        ]);


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
        } elseif (Yii::$app->getRequest()->getCookies()->getValue('route_map_tab') !== null) {
            $route_id = Yii::$app->getRequest()->getCookies()->getValue('route_map_tab');
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

    protected function setPostMarkers(&$map, $date)
    {
        $model = Posten::find()
            ->where([
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'date' => $date
        ]);

        if (!$model->exists()) {
            return;
        }

        $icon = new Icon(['url' => Url::to('@web/images/map_icons/star-3.png')]);
        foreach ($model->all() as $post) {
            if ($post->latitude === null) {
                $latitude = 0.000;
            } else {
                $latitude = $post->latitude;
            }

            if ($post->longitude === null) {
                $longitude = 0.0000;
            } else {
                $longitude = $post->longitude;
            }

            $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);

            // Lets add a marker now
            $marker = new Marker([
                'position' => $coord,
                'title' => $post->post_name,
                'icon' => $icon,
                'draggable' => true,
            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => '<a href="' . Url::to(['posten/update', 'post_ID' => $post->post_ID], true) . '" target="_blank">' . $post->post_name . '</a>'
                ])
            );

            $link = Url::to(['posten/ajaxupdate'], true);
            $event = new Event([
                "trigger" => "dragend",
                "js" =>
                "
                    function savePost () {
                        latitude = event.latLng.lat()
                        longitude = event.latLng.lng();
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                post_id: $post->post_ID,
                                map: true
                            },
                            success: function (data) {
                            },
                            error: function(jqXHR, errMsg) {
                                // handle error
                                alert(errMsg + data);
                            }
                        });
                    };
         
                    krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                        if (result) {
                            savePost();
                        } else {
                            location.reload();
                        }
                    });

                "
            ]);

            $marker->addEvent($event);
            // Add marker to the map
            $map->addOverlay($marker);
        }
    }

    protected function setQrMarkers(&$map, $route_id)
    {
        $model = Qr::find()
            ->where([
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'route_ID' => $route_id
        ]);

        if (!$model->exists()) {
            return;
        }

        $icon = new Icon(['url' => Url::to('@web/images/map_icons/qr-code.png')]);
        foreach ($model->all() as $post) {
            if ($post->latitude === null) {
                $latitude = 0.0000;
            } else {
                $latitude = $post->latitude;
            }

            if ($post->longitude === null) {
                $longitude = 0.000;
            } else {
                $longitude = $post->longitude;
            }

            $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);

            // Lets add a marker now
            $marker = new Marker([
                'position' => $coord,
                'title' => $post->qr_name,
                'icon' => $icon,
                'draggable' => true,
            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => '<a href="' . Url::to(['qr/update', 'qr_ID' => $post->qr_ID], true) . '" target="_blank">' . $post->qr_name . '</a>'
                ])
            );

            $link = Url::to(['qr/ajaxupdate'], true);
            $event = new Event([
                "trigger" => "dragend",
                "js" =>
                "
                    function saveQr () {
                        latitude = event.latLng.lat()
                        longitude = event.latLng.lng();
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                qr_id: $post->qr_ID,
                                map: true
                            },
                            success: function (data) {
                            },
                            error: function(jqXHR, errMsg) {
                                // handle error
                                alert(errMsg + data);
                            }
                        });
                    };

                    krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                        if (result) {
                            saveQr();
                        } else {
                            location.reload();
                        }
                    });
                "
            ]);

            $marker->addEvent($event);
            // Add marker to the map
            $map->addOverlay($marker);
        }
    }

    protected function setHintMarkers(&$map, $route_id)
    {
        $model = NoodEnvelop::find()
            ->where([
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'route_ID' => $route_id
        ]);

        if (!$model->exists()) {
            return;
        }

        $icon = new Icon(['url' => Url::to('@web/images/map_icons/postal.png')]);
        foreach ($model->all() as $post) {
            if ($post->latitude === null) {
                $latitude = 0.000;
            } else {
                $latitude = $post->latitude;
            }

            if ($post->longitude === null) {
                $longitude = 0.000;
            } else {
                $longitude = $post->longitude;
            }

            $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);

            // Lets add a marker now
            $marker = new Marker([
                'position' => $coord,
                'title' => $post->nood_envelop_name,
                'icon' => $icon,
                'draggable' => true,
            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => '<a href="' . Url::to(['nood-envelop/update', 'nood_envelop_ID' => $post->nood_envelop_ID], true) . '" target="_blank">' . $post->nood_envelop_name . '</a>'
                ])
            );

            $link = Url::to(['nood-envelop/ajaxupdate'], true);
            $event = new Event([
                "trigger" => "dragend",
                "js" =>
                "
                    function saveHint () {
                        latitude = event.latLng.lat()
                        longitude = event.latLng.lng();
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                nood_envelop_id: $post->nood_envelop_ID,
                                map: true
                            },
                            success: function (data) {
                            },
                            error: function(jqXHR, errMsg) {
                                // handle error
                                alert(errMsg + data);
                            }
                        });
                    };

                    krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                        if (result) {
                            saveHint();
                        } else {
                            location.reload();
                        }
                    });
                "
            ]);

            $marker->addEvent($event);
            // Add marker to the map
            $map->addOverlay($marker);
        }
    }

    protected function setvragenMarkers(&$map, $route_id)
    {
        $model = OpenVragen::find()
            ->where([
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'route_ID' => $route_id
        ]);

        if (!$model->exists()) {
            return;
        }

        $icon = new Icon(['url' => Url::to('@web/images/map_icons/notvisited.png')]);
        foreach ($model->all() as $post) {
            if ($post->latitude === null) {
                $latitude = 0.0000;
            } else {
                $latitude = $post->latitude;
            }

            if ($post->longitude === null) {
                $longitude = 0.000;
            } else {
                $longitude = $post->longitude;
            }

            $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);

            // Lets add a marker now
            $marker = new Marker([
                'position' => $coord,
                'title' => $post->open_vragen_name,
                'icon' => $icon,
                'draggable' => true,
            ]);

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => '<a href="' . Url::to(['open-vragen/update', 'open_vragen_ID' => $post->open_vragen_ID], true) . '" target="_blank">' . $post->open_vragen_name . '</a>'
                ])
            );

            $link = Url::to(['open-vragen/ajaxupdate'], true);
            $event = new Event([
                "trigger" => "dragend",
                "js" =>
                "
                    function saveQuestion () {
                        latitude = event.latLng.lat()
                        longitude = event.latLng.lng();
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                open_vragen_id: $post->open_vragen_ID,
                                map: true
                            },
                            success: function (data) {
                            },
                            error: function(jqXHR, errMsg, data) {
                                // handle error
                                alert(errMsg + data);
                            }
                        });
                    };

                    krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                        if (result) {
                            saveQuestion();
                        } else {
                            location.reload();
                        }
                    });
                "
            ]);

            $marker->addEvent($event);
            // Add marker to the map
            $map->addOverlay($marker);
        }
    }

    protected function setTimeTrailMarkers(&$map)
    {
        $model = TimeTrail::find()
            ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
            ])
            ->orderBy([
                'time_trail_ID' => SORT_ASC
            ]);

        if (!$model->exists()) {
            return;
        }

        $kleur = 0;
        foreach ($model->all() as $timeTrail) {
            $items = TimeTrailItem::find()
                ->where([
                    'time_trail_ID' => $timeTrail->time_trail_ID,
                ])
                ->orderBy([
                    'volgorde' => SORT_ASC
                ])
                ->all();
            $count = 1;
            foreach ($items as $item) {
                $kleuren = new CustomMap();
                $icon = new Icon(['url' => Url::to('@web/images/map_icons/' . $kleuren->kleuren[$kleur] . '_' . $count . '.png')]);
                if ($item->latitude === null) {
                    $latitude = 0.0000;
                } else {
                    $latitude = $item->latitude;
                }

                if ($item->longitude === null) {
                    $longitude = 0.000;
                } else {
                    $longitude = $item->longitude;
                }

                $coord = new LatLng(['lat' => $latitude, 'lng' => $longitude]);

                // Lets add a marker now
                $marker = new Marker([
                    'position' => $coord,
                    'title' => $item->time_trail_item_name,
                    'icon' => $icon,
                    'draggable' => true,
                ]);

                // Provide a shared InfoWindow to the marker
                $marker->attachInfoWindow(
                    new InfoWindow([
                    'content' => '<a href="' . Url::to(['time-trail-item/update', 'time_trail_item_ID' => $item->time_trail_item_ID], true) . '" target="_blank">' . $item->time_trail_item_name . '</a>'
                    ])
                );

                $link = Url::to(['time-trail-item/ajaxupdate'], true);
                $event = new Event([
                    "trigger" => "dragend",
                    "js" =>
                    "
                        function saveTrailItem () {
                            latitude = event.latLng.lat()
                            longitude = event.latLng.lng();
                            $.ajax({
                                url: '$link',
                                type: 'POST',
                                data: {
                                    latitude: latitude,
                                    longitude: longitude,
                                    time_trail_item_ID: $item->time_trail_item_ID,
                                    map: true
                                },
                                success: function (data) {
                                },
                                error: function(jqXHR, errMsg, data) {
                                    // handle error
                                    alert(errMsg + data);
                                }
                            });
                        };

                        krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                            if (result) {
                                saveTrailItem();
                            } else {
                                location.reload();
                            }
                        });
                    "
                ]);

                $marker->addEvent($event);
                // Add marker to the map
                $map->addOverlay($marker);
                $count++;
            }
            $kleur++;
        }
    }
}
