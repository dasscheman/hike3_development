<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use app\components\GeneralFunctions;
use app\models\Groups;
use app\models\Locate;
use dosamigos\leaflet\LeafLet;
use dosamigos\leaflet\types\LatLng;
use dosamigos\leaflet\types\LatLngBounds;
use dosamigos\leaflet\types\Icon;
use dosamigos\leaflet\types\Point;
use dosamigos\leaflet\layers\Marker;
use dosamigos\leaflet\layers\PolyLine;
use dosamigos\leaflet\plugins\markercluster\MarkerCluster;
use yii\db\ActiveQuery;
use yii\caching\TagDependency;

class OpenMap extends LeafLet
{
    public $kleuren = ['red', 'blue', 'orange', 'purple', 'green', 'yellow', 'turqouise'];
    public $counts = [];
    public $cluster;
    public $iconSettings = [];
    public $allCoordinates = [];
    public $allCoordinatesArray = [];

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->iconSettings =  [
            'shadowUrl' => Url::to('@web/images/map_icons/marker-shadow.png'),
            'iconSize' =>  new Point(['x' =>32, 'y' => 37]),
            'iconAnchor' => new Point(['x' => 10, 'y' => 32]),
            'popupAnchor' => new Point(['x' => 1, 'y' => -32]),
            'shadowAnchor' => new Point(['x' => 4, 'y' => 12]),
            'shadowSize' => new Point(['x' => 36, 'y' => 16]),
        ];
        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $this->center = $coord;


        $tileLayer = new \dosamigos\leaflet\layers\TileLayer([
           'urlTemplate' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'clientOptions' => [
                'attribution' => '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
            //     'subdomains' => ['1', '2', '3', '4'],
            ],
        ]);

        $this->addLayer($tileLayer);  // add the tile layer

        $this->cluster = new MarkerCluster([
            'clientOptions' => [
                'showCoverageOnHover' => false,
                'maxClusterRadius' => 15
                // 'polygonOptions' => [
                //     'fillColor' => '#1b2557',
                //     'color' => 'red',
                //     'weight' => 0.5,
                //     'opacity' => 10,
                //     'fillOpacity' => 10
                // ]
            ]
        ]);

        parent::__construct($config);
    }

    public function setCookieIndexRoute($route_id)
    {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookies->remove('route_map_tab' . Yii::$app->user->id);
        $cookie = new Cookie([
            'name' => 'route_map_tab' . Yii::$app->user->id,
            'value' => $route_id,
            'expire' => time() + 86400 * 365,
        ]);
        $cookies->add($cookie);
    }

    public function setPostMarkers($date, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getPosten();
        } else {
            $model = Posten::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID
                ]);
        }

        if (!$model->exists()) {
            return;
        }

        foreach ($model->all() as $post) {
            $content = 'Post Passages ' . $post->post_name . '<br>';
            if (!$model->exists()) {
                $content .= Yii::t('app', 'No groups have past this station');
            }
            $coord = $this->getCoordinates($post);

            if ($edit) {
                $content = '<a href="' . Url::to(['posten/map-update', 'post_ID' => $post->post_ID], true) . '" target="_blank">' . $post->post_name . '</a>';
            } else {
                $passages = $post->getPostPassages()->orderBy(['binnenkomst' => SORT_DESC]);

                $count = 0;
                foreach ($passages->all() as $passage) {
                    $binnenkomst = \Yii::$app->formatter->asDate($passage->binnenkomst, 'php:d-M H:i');
                    $vertrek = \Yii::$app->formatter->asDate($passage->vertrek, 'php:d-M H:i');
                    $content .= $passage->getGroupName() . ' <i>' . $binnenkomst . ' - ' . $vertrek . '</i></br>';
                    $count++;
                }
            }

            $link = Url::to(['posten/ajaxupdate'], true);
            $event = $this->getDragEndEvent($link, $post->post_ID);

            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/star-3.png');
            $icon = new Icon(
                $this->iconSettings
            );

            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $content,
                'clientOptions' => [
                    'icon' => $icon,
                    'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);

            $this->cluster->addMarker($marker);
            array_push($this->allCoordinates, $coord);
            array_push($this->allCoordinatesArray, $coord->toArray());
        }
        $this->installPlugin($this->cluster);
    }


    public function setQrMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getQr();
        } else {
            $model = Qr::find()
                ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID
            ]);
        }

        if (!$model->exists()) {
            return;
        }

        foreach ($model->all() as $post) {
            $content = 'Stille post: ' . $post->qr_name . '<br>';
            if (!$model->exists()) {
                $content .= Yii::t('app', 'No groups have checked this silent station');
            }
            $coord = $this->getCoordinates($post);
            if ($edit) {
                $content = '<a href="' . Url::to(['qr/map-update', 'qr_ID' => $post->qr_ID], true) . '" target="_blank">' . $post->qr_name . '</a>';
            } else {
                $checks = $post->getQrChecks()->orderBy(['create_time' => SORT_DESC]);

                if ($group) {
                    $checks->andWhere(['group_ID' => $group]);
                }
                $count = 0;
                foreach ($checks->all() as $check) {
                    $binnenkomst = \Yii::$app->formatter->asDate($check->create_time, 'php:d-M H:i');
                    $content .= $check->getGroupName() . ' <i>' . $binnenkomst .'</i></br>';
                    $count++;
                }
            }

            $link = Url::to(['qr/ajaxupdate'], true);
            $event = $this->getDragEndEvent($link, $post->qr_ID);

            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/qr-code.png');
            $icon = new Icon(
                $this->iconSettings
            );

            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $content,
                'clientOptions' => [
                    'icon' => $icon,
                    'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);
            $this->cluster->addMarker($marker);
            array_push($this->allCoordinates, $coord);
            array_push($this->allCoordinatesArray, $coord->toArray());
        }
        $this->installPlugin($this->cluster);
    }

    public function setHintMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getNoodEnvelop();
        } else {
            $model = NoodEnvelop::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID
                ]);
        }
        // When in group overview, display hint on map only when this attribute is set.
        if ($group) {
            $model->andWhere(['show_coordinates' => true]);
        }

        if (!$model->exists()) {
            return;
        }

        foreach ($model->all() as $post) {
            $coord = $this->getCoordinates($post);

            $content = 'Hints ' . $post->nood_envelop_name . '<br>';
            $content .= Yii::t('app', 'No group have opened this hint');
            if ($edit) {
                $content = '<a href="' . Url::to(['nood-envelop/map-update', 'nood_envelop_ID' => $post->nood_envelop_ID], true) . '" target="_blank">' . $post->nood_envelop_name . '</a>';
            } else {
                $hints = $post->getOpenNoodEnvelops()->orderBy(['create_time' => SORT_DESC]);
                if ($group) {
                    $hints->andWhere(['group_ID' => $group]);
                }
                $count = 0;
                foreach ($hints->all() as $hint) {
                    $binnenkomst = \Yii::$app->formatter->asDate($hint->create_time, 'php:d-M H:i');
                    if ($group) {
                        $content .= '<i>' . $post->opmerkingen .'</i></br>';
                    } else {
                        $content .= $hint->getGroupName() . ' <i>' . $binnenkomst .'</i></br>';
                    }
                    $count++;
                }
            }

            $link = Url::to(['nood-envelop/ajaxupdate'], true);
            $event = $this->getDragEndEvent($link, $post->nood_envelop_ID);

            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/postal.png');
            $icon = new Icon(
                $this->iconSettings
            );
            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $content,
                'clientOptions' => [
                    'icon' => $icon,
                    'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);

            array_push($this->allCoordinates, $coord);
            array_push($this->allCoordinatesArray, $coord->toArray());
            $this->cluster->addMarker($marker);
        }
        $this->installPlugin($this->cluster);
    }

    public function setVragenMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getVraagAwnseredCorrecly();
        } else {
            $model = OpenVragen::find()
                ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID
            ]);
        }

        if (!$model->exists()) {
            return;
        }

        foreach ($model->all() as $post) {
            $coord = $this->getCoordinates($post);
            $content = Yii::t('app', 'No group have opened this hint');
            if ($edit) {
                $content = '<a href="' . Url::to(['open-vragen/map-update', 'open_vragen_ID' => $post->open_vragen_ID], true) . '" target="_blank">' . $post->open_vragen_name . '</a>';
            } else {
                $vragen = $post->getOpenVragenAntwoordens()->orderBy(['create_time' => SORT_DESC]);
                if ($group) {
                    $vragen->andWhere(['group_ID' => $group]);
                }
                $count = 0;
                foreach ($vragen->all() as $vraag) {
                    if ($count === 0) {
                        $content = 'Question: ' . $vraag->getOpenVragen()->one()->open_vragen_name . '<br>';
                    }
                    $binnenkomst = \Yii::$app->formatter->asDate($vraag->create_time, 'php:d-M H:i');
                    if ($vraag->checked) {
                        $icon2 = GeneralFunctions::printGlyphiconCheck($vraag->correct);
                    } else {
                        $icon2 = '<span class="glyphicon glyphicon-question-sign"></span> ';
                    }
                    $content .= $icon2 . ' ' . $vraag->getGroupName() . ' <i>' . $binnenkomst .'</i></br>';
                    $count++;
                }
            }

            $link = Url::to(['open-vragen/ajaxupdate'], true);
            $event = $this->getDragEndEvent($link, $post->open_vragen_ID);

            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/notvisited.png');
            $icon = new Icon(
                $this->iconSettings
            );
            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $content,
                'clientOptions' => [
                    'icon' => $icon,
                    'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);

            array_push($this->allCoordinates, $coord);
            array_push($this->allCoordinatesArray, $coord->toArray());
            $this->cluster->addMarker($marker);
        }
        $this->installPlugin($this->cluster);
    }

    public function setTimeTrailMarkers($edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getTimeTrail();
        } else {
            $model = TimeTrail::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID,
                ])
                ->orderBy([
                    'time_trail_ID' => SORT_ASC
                ]);
        }

        if (!$model->exists()) {
            return;
        }

        $kleuren = new OpenMap();
        $kleur = 0;
        foreach ($model->all() as $timeTrail) {
            $items = $timeTrail->getTimeTrailItems();
            $countitems = 1;
            if ($items->all() == null) {
                continue;
            }

            $count_items = 1;
            foreach ($items->all() as $item) {
                $coord = $this->getCoordinates($item);
                $content = 'Time trail ' . $item->getTimeTrail()->one()->time_trail_name . '<br>'
                  . $item->time_trail_item_name . '<br>';
                if ($edit) {
                    $content = '<a href="' . Url::to(['time-trail-item/map-update', 'time_trail_item_ID' => $item->time_trail_item_ID], true) . '" target="_blank">' . $item->time_trail_item_name . '</a>';
                } else {
                    $checks = $item->getTimeTrailChecks()->orderBy(['create_time' => SORT_DESC]);
                    if ($group) {
                        $checks->andWhere(['group_ID' => $group]);
                        if(!$checks->exists()) {
                            // als voor een group de check niet bestaat dan naar
                            // het volgend item.
                            continue;
                        }
                    }

                    if (!$checks->exists()) {
                        $content .= Yii::t('app', 'No group have checked this time trail item');
                    }
                    foreach ($checks->all() as $check) {
                        $start = Yii::$app->setupdatetime->displayFormat($check->start_time, 'datetime_no_sec', false, true);
                        $eind = Yii::$app->setupdatetime->displayFormat($check->end_time, 'datetime_no_sec', false, true);
                        if (isset($check->end_time)) {
                            $icon2 = GeneralFunctions::printGlyphiconCheck($check->succeded);
                        } else {
                            $icon2 = '<span class="glyphicon glyphicon-question-sign"></span> ';
                            $eind = Yii::t('app', 'volgende punt nog niet gescand');
                        }
                        $content .= $icon2 . ' ' . $check->getGroupName() . ' <i>' . $start . ' - ' . $eind . '</i></br>';
                    }
                }

                $link = Url::to(['time-trail-item/ajaxupdate'], true);
                $event = $this->getDragEndEvent($link, $item->time_trail_item_ID);

                $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/marker/' . $kleuren->kleuren[fmod($kleur, 6)] . '_' . $countitems . '.png');
                $icon = new Icon(
                    $this->iconSettings
                );

                // Lets add a marker now
                $marker = new Marker([
                    'latLng' => $coord,
                    'popupContent' => $content,
                    'clientOptions' => [
                        'icon'	=>  $icon,
                        'draggable' => $edit,
                    ],
                    'clientEvents' => $event
                ]);
                array_push($this->allCoordinates, $coord);
                array_push($this->allCoordinatesArray, $coord->toArray());
                $this->cluster->addMarker($marker);
                $countitems++;
            }
            $this->installPlugin($this->cluster);
            $kleur++;
        }
    }

    public function setEventTrack()
    {
        $content = '';
        $generalfucntions = new GeneralFunctions;
        $trackNames = RouteTrack::find()
            ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'type' => RouteTrack::TYPE_track
            ])
            ->groupBy('name')
            ->all();

        foreach($trackNames as $trackName) {
            $coordinates = [];
            $models = RouteTrack::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID,
                    'type' => RouteTrack::TYPE_track,
                    'name' => $trackName->name])
                ->all();

            foreach ($models as $model) {
                array_push($coordinates, $this->getCoordinates($model));
            }

            $path = new PolyLine([
                'popupContent' => $trackName->name,
                'clientOptions' => ['color' => '#' . $generalfucntions->random_color()]
            ]);
            $path->setLatLngs($coordinates);
            $this->addLayer($path);
        }
    }

    public function setOrganisationMarkers()
    {
        $organisations = DeelnemersEvent::find()
            ->where([
              'event_ID' => Yii::$app->user->identity->selected_event_ID
            ])
            ->all();

        foreach ($organisations as $organisation) {
            $model = Track::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID,
                    'user_ID' => $organisation->user_ID
                ])
                ->orderBy(['timestamp' => SORT_DESC]);


            if (!$model->exists()) {
                return;
            }
            $waypoint = $model->one();
            $coord = $this->getCoordinates($waypoint);

            $content = $waypoint->getUserName() . ': ' . \Yii::$app->formatter->asDate($waypoint->timestamp, 'php:d-M H:i');

            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/comment-map-icon.png');
            $icon = new Icon(
                $this->iconSettings
            );
            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $content,
                'clientOptions' => [
                    'icon' => $icon,
                ],
            ]);

            array_push($this->allCoordinates, $coord);
            array_push($this->allCoordinatesArray, $coord->toArray());
            $this->cluster->addMarker($marker);
        }
        $this->installPlugin($this->cluster);
    }

    public function setTrackGroups()
    {
        $content = '';
        $generalfucntions = new GeneralFunctions;
        $groups = Groups::find()
            ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID
            ])
            ->all();

        foreach($groups as $group) {
            $coordinates = [];
            $models = Track::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID,
                    'group_ID' => $group->group_ID
                ])
                ->all();

            foreach ($models as $model) {
                array_push($coordinates, $this->getCoordinates($model));
            }

            $path = new PolyLine([
                'popupContent' => $group->group_name,
                'clientOptions' => ['color' => '#' . $generalfucntions->random_color()]
            ]);
            $path->setLatLngs($coordinates);
            $this->addLayer($path);
        }
    }

    public function setLocate(){
        $options = [
            'position' => 'topleft',
            'clientOptions' => [
                'strings' => [
                    'title' => 'waar ben ik?'
                ],
                'flyto' => 'true',
                'icon' => 'glyphicon glyphicon-map-marker']
        ];
        $locate = new Locate($options);
        $this->addControl($locate);
    }

    public function setEventTrackPoints()
    {
        $coordinates = [];
        $models = RouteTrack::find()
            ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'type' => RouteTrack::TYPE_track])
            ->all();

        $iconSettings = $this->iconSettings;
        $iconSettings['iconSize'] =  new Point(['x' =>10, 'y' => 10]);
        $iconSettings['iconUrl'] = Url::to('@web/images/map_icons/circle.jpg');

        $iconSettings['iconAnchor'] = new Point(['x' => 1, 'y' => 1]);
        $iconSettings['shadowUrl'] = '';

        foreach ($models as $model) {
            $coord = $this->getCoordinates($model);
            array_push($this->allCoordinatesArray, $coord->toArray());
            $icon = new Icon(
                $iconSettings
            );

            $link = Url::to(['route-track/ajax-delete'], true);
            $event = $this->getRightMouseEvent($link, $model->route_track_ID);

            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $model->name . ': ' . $model->latitude . ', ' . $model->longitude,
                'clientOptions' => [
                    'icon' => $icon,
                    'iconSize' => [
                        '130px',
                        '130px'
                    ]
                    // 'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);
            $this->addLayer($marker);
            // $this->cluster->addMarker($marker);
        }
        // $this->installPlugin($this->cluster);
    }

    public function setEventWayPoints()
    {
        $coordinates = [];
        $models = RouteTrack::find()
            ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'type' => RouteTrack::TYPE_waypoint])
            ->all();

        foreach ($models as $model) {
            $coord = $this->getCoordinates($model);
            $this->iconSettings['iconUrl'] = Url::to('@web/images/map_icons/footprint.png');
            $icon = new Icon(
                $this->iconSettings
            );

            $link = Url::to(['route-track/ajax-delete'], true);
            $event = $this->getRightMouseEvent($link, $model->route_track_ID);

            // Lets add a marker now
            $marker = new Marker([
                'latLng' => $coord,
                'popupContent' => $model->name . ' - RD: ' . $model->getLatitude() . ', ' . $model->getLongitude(),
                'clientOptions' => [
                    'icon' => $icon,
                    // 'draggable' => $edit,
                ],
                'clientEvents' => $event
            ]);

            $this->cluster->addMarker($marker);
        }
        $this->installPlugin($this->cluster);
    }

    public function getDragableMarker()
    {
        $cordinates = $this->allCoordinates;
        if(empty($cordinates)) {
            // zet de default pointer op de bison.
            $lat = 52.0825;
            $lng = 5.2643;
        } else {
            $bounds = LatLngBounds::getBoundsOfLatLngs($cordinates);
            $lat = ($bounds->northEast->lat + $bounds->southWest->lat) / 2;
            $lng = ($bounds->northEast->lng + $bounds->southWest->lng) / 2;

        }
        $coords = new LatLng(['lat' => $lat, 'lng' => $lng]);
        $marker = new Marker([
            'latLng' => $coords,
            'popupContent' => Yii::t('app', 'Verplaats de marker naar waar een een item toe wilt voegen'),
            'clientOptions' => [
                 'draggable' => true,
                 'id' => 'pointer_id'
             ],
             'clientEvents' => $this->getDragEvent()
        ]);
        $this->addLayer($marker);      // add the marker
        return $marker;
    }

    /*
     * Gets the coordinates from any model with long and lat
     */
    public function getCoordinates($model)
    {
        if ($model->latitude === null) {
            $latitude = 0.000;
        } else {
            $latitude = $model->latitude;
        }

        if ($model->longitude === null) {
            $longitude = 0.0000;
        } else {
            $longitude = $model->longitude;
        }

        return new LatLng(['lat' => $latitude, 'lng' => $longitude]);
    }

    /**
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findGroupModel($id)
    {
        $model = Groups::findOne([
                'group_ID' => $id,
                'event_ID' => Yii::$app->user->identity->selected_event_ID]);

        if ($model !== null) {
            return $model;
        } else {
            return false;
        }
    }

    public function getDragEvent()
    {
        $event["drag"] =
            "
                function(event){
                    proj4.defs('EPSG:28992', '+proj=sterea +lat_0=52.15616055555555 +lon_0=5.38763888888889 +k=0.9999079 +x_0=155000 +y_0=463000 +ellps=bessel +units=m +towgs84=565.2369,50.0087,465.658,-0.406857330322398,0.350732676542563,-1.8703473836068,4.0812 +no_defs');
                    var rd=proj4('EPSG:28992', [event.target.getLatLng().lng, event.target.getLatLng().lat]);
                    document.getElementById('latitude').innerHTML = rd[0].toFixed(0);
                    document.getElementById('longitude').innerHTML = rd[1].toFixed(0);

                    function setLngLat() {
                        x=document.getElementsByClassName('latitude');
                        for(var i = 0; i < x.length; i++){
                            x[i].value=event.target.getLatLng().lat;    // Change the content
                        }
                        y=document.getElementsByClassName('longitude');

                        for(var i = 0; i < y.length; i++){
                            y[i].value=event.target.getLatLng().lng;    // Change the content
                        }
                        x=document.getElementsByClassName('latitude-rd');
                        for(var i = 0; i < x.length; i++){
                            x[i].innerHTML=rd[0].toFixed(0)    // Change the content
                        }
                        y=document.getElementsByClassName('longitude-rd');

                        for(var i = 0; i < y.length; i++){
                            y[i].innerHTML=rd[1].toFixed(0);    // Change the content
                        }
                    }
                    setLngLat()
                }
            ";
        return $event;
    }

    public function getDragEndEvent($link, $id)
    {
        $event['dragend'] =
        "
            function(event){
                document.getElementById('latitude').innerHTML = event.target.getLatLng().lat.toFixed(6);
                document.getElementById('longitude').innerHTML = event.target.getLatLng().lng.toFixed(6);

                function setLngLat() {
                    x=document.getElementsByClassName('latitude');
                    for(var i = 0; i < x.length; i++){
                        x[i].innerHTML=event.target.getLatLng().lat;    // Change the content
                    }
                    y=document.getElementsByClassName('longitude');

                    for(var i = 0; i < y.length; i++){
                        y[i].innerHTML=event.target.getLatLng().lng;    // Change the content
                    }
                }
                setLngLat()

                krajeeDialog.confirm('" . Yii::t('app', 'Weet je zeker dat je de nieuwe lokatie wilt opslaan?') . "', function (result) {
                    if (result) {
                        latitude = event.target.getLatLng().lat
                        longitude = event.target.getLatLng().lng;
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                latitude: latitude,
                                longitude: longitude,
                                id: $id,
                                map: true
                            },
                            success: function (data) {

                                if(data !== '1') {
                                    alert(data);
                                }
                            },
                            error: function(jqXHR, errMsg, data) {
                                alert(errMsg + data);
                            }
                        })
                    } else {
                        location.reload();
                    }
                })
            }
        ";

        return $event;
    }

    public function getRightMouseEventConfirm($link, $id)
    {
        $event['contextmenu'] =
        "
            function(event){
                krajeeDialog.confirm('" . Yii::t('app', 'Weet je zeker dat je dit waypoint wilt verwijderen?') . "', function (result) {
                    if (result) {
                        $.ajax({
                            url: '$link',
                            type: 'POST',
                            data: {
                                id: $id,
                                map: true
                            },
                            success: function (data) {
                                if(data !== '1') {
                                    alert(data);
                                }
                                map.removeLayer(event.target);
                            },
                            error: function(jqXHR, errMsg, data) {
                                alert(errMsg + data);
                            }
                        })
                    }
                })
            }
        ";

        return $event;
    }

    public function getRightMouseEvent($link, $id)
    {
        $event['contextmenu'] =
        "
            function(event){
                $.ajax({
                    url: '$link',
                    type: 'POST',
                    data: {
                        id: $id,
                        map: true
                    },
                    success: function (data) {
                        if(data !== '1') {
                            alert(data);
                        }
                        map.removeLayer(event.target);
                    },
                    error: function(jqXHR, errMsg, data) {
                        alert(errMsg + data);
                    }
                })
            }
        ";

        return $event;
    }
}
