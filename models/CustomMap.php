<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\web\Cookie;
use yii\helpers\ArrayHelper;
use app\models\Groups;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Icon;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\Event;
use dosamigos\google\maps\overlays\SymbolPath;
use dosamigos\google\maps\overlays\Symbol;
use app\components\GeneralFunctions;
use yii\db\ActiveQuery;
use yii\caching\TagDependency;

class CustomMap extends Map
{
    public $kleuren = ['rood', 'geel', 'blauw', 'oranje', 'paars', 'groen'];
    public $counts = [];

    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        $coord = new LatLng(['lat' => 52.082689705630365, 'lng' => 5.264233018789355]);
        $this->options = ArrayHelper::merge(
            [
                'center' => $coord,
                'zoom' => 14,
            ],
            $this->options
        );
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
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'date' => $date
            ]);
        }

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
                'draggable' => $edit,
            ]);

            $content = Yii::t('app', 'No groups have past this station');
            if ($edit) {
                $content = '<a href="' . Url::to(['posten/map-update', 'post_ID' => $post->post_ID], true) . '" target="_blank">' . $post->post_name . '</a>';
            } else {
                $passages = $post->getPostPassages()->orderBy(['binnenkomst' => SORT_DESC]);

                $count = 0;
                foreach ($passages->all() as $passage) {
                    if ($count === 0) {
                        $content = 'Post Passages ' . $passage->getPost()->one()->post_name . '<br>';
                    }
                    $binnenkomst = \Yii::$app->formatter->asDate($passage->binnenkomst, 'php:d-M H:i');
                    $vertrek = \Yii::$app->formatter->asDate($passage->vertrek, 'php:d-M H:i');
                    $content .= $passage->getGroupName() . ' <i>' . $binnenkomst . ' - ' . $vertrek . '</i></br>';
                    $count++;
                }
            }

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => $content
                ])
            );

            $link = Url::to(['posten/ajaxupdate'], true);

            $event = $this->getEvent($link, $post->post_ID);

            $marker->addEvent($event);
            // Add marker to the map
            $this->addOverlay($marker);
        }
    }

    public function setQrMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getQr();
        } else {
            $model = Qr::find()
                ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'route_ID' => $route_id
            ]);
        }

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
                'draggable' => $edit,
            ]);

            $content = Yii::t('app', 'No groups have checked this silent station');
            if ($edit) {
                $content = '<a href="' . Url::to(['qr/map-update', 'qr_ID' => $post->qr_ID], true) . '" target="_blank">' . $post->qr_name . '</a>';
            } else {
                $checks = $post->getQrChecks()->orderBy(['create_time' => SORT_DESC]);

                if ($group) {
                    $checks->andWhere(['group_ID' => $group]);
                }
                $count = 0;
                foreach ($checks->all() as $check) {
                    if ($count === 0) {
                        $content = 'Silent station ' . $check->getQr()->one()->qr_name . '<br>';
                    }
                    $binnenkomst = \Yii::$app->formatter->asDate($check->create_time, 'php:d-M H:i');
                    $content .= $check->getGroupName() . ' <i>' . $binnenkomst .'</i></br>';
                    $count++;
                }
            }

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => $content
                ])
            );

            $link = Url::to(['qr/ajaxupdate'], true);

            $event = $this->getEvent($link, $post->qr_ID);

            $marker->addEvent($event);
            // Add marker to the map
            $this->addOverlay($marker);
        }
    }

    public function setHintMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getNoodEnvelop();
        } else {
            $model = NoodEnvelop::find()
                ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'route_ID' => $route_id
            ]);
        }
        // When in group overview, display hint on map only when this attribute is set.
        if ($group) {
            $model->andWhere(['show_coordinates' => true]);
        }

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
                'draggable' => $edit,
            ]);

            $content = Yii::t('app', 'No group have opened this hint');
            if ($edit) {
                $content = '<a href="' . Url::to(['nood-envelop/map-update', 'nood_envelop_ID' => $post->nood_envelop_ID], true) . '" target="_blank">' . $post->nood_envelop_name . '</a>';
            } else {
                $hints = $post->getOpenNoodEnvelops()->orderBy(['create_time' => SORT_DESC]);
                if ($group) {
                    $hints->andWhere(['group_ID' => $group]);
                }
                $count = 0;
                foreach ($hints->all() as $hint) {
                    if ($count === 0) {
                        $content = 'Hints ' . $hint->getNoodEnvelop()->one()->nood_envelop_name . '<br>';
                    }
                    $binnenkomst = \Yii::$app->formatter->asDate($hint->create_time, 'php:d-M H:i');
                    $content .= $hint->getGroupName() . ' <i>' . $binnenkomst .'</i></br>';
                    $count++;
                }
            }

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => $content
                ])
            );

            $link = Url::to(['nood-envelop/ajaxupdate'], true);

            $event = $this->getEvent($link, $post->nood_envelop_ID);

            $marker->addEvent($event);
            // Add marker to the map
            $this->addOverlay($marker);
        }
    }

    public function setVragenMarkers($route_id, $edit = false, $group = false)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getVraagAwnseredCorrecly();
        } else {
            $model = OpenVragen::find()
                ->where([
                'event_ID' => Yii::$app->user->identity->selected_event_ID,
                'route_ID' => $route_id
            ]);
        }

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
                'draggable' => $edit,
            ]);


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

            // Provide a shared InfoWindow to the marker
            $marker->attachInfoWindow(
                new InfoWindow([
                'content' => $content
                ])
            );

            $link = Url::to(['open-vragen/ajaxupdate'], true);
            $event = $this->getEvent($link, $post->open_vragen_ID);
            $marker->addEvent($event);
            // Add marker to the map
            $this->addOverlay($marker);
        }
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

        $kleur = 0;
        foreach ($model->all() as $timeTrail) {
            if ($group) {
                $items = $timeTrail->getTimeTrailItemsCheckedByGroup($group);
            } else {
                $items = $timeTrail->getTimeTrailItems();
            }
            $countitems = 1;
            $kleuren = new CustomMap();
            if ($items->all() == null) {
                continue;
            }

            foreach ($items->all() as $item) {
                $icon = new Icon(['url' => Url::to('@web/images/map_icons/' . $kleuren->kleuren[fmod($kleur, 5)] . '_' . $countitems . '.png')]);
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
                    'draggable' => $edit,
                ]);


                $content = Yii::t('app', 'No group have checked this time trail item');
                if ($edit) {
                    $content = '<a href="' . Url::to(['time-trail-item/map-update', 'time_trail_item_ID' => $item->time_trail_item_ID], true) . '" target="_blank">' . $item->time_trail_item_name . '</a>';
                } else {
                    $checks = $item->getTimeTrailChecks()->orderBy(['create_time' => SORT_DESC]);
                    if ($group) {
                        $checks->andWhere(['group_ID' => $group]);
                    }
                    $countgroups = 0;
                    foreach ($checks->all() as $check) {
                        if ($countgroups === 0) {
                            $content = 'Time trail ' . $check->getTimeTrailItem()->one()->getTimeTrail()->one()->time_trail_name . ' ' . $check->getTimeTrailItem()->one()->time_trail_item_name . '<br>';
                        }
                        $start = \Yii::$app->formatter->asDate($check->start_time, 'php:d-M H:i');
                        $eind = \Yii::$app->formatter->asDate($check->end_time, 'php:d-M H:i');
                        if (isset($check->end_time)) {
                            $icon2 = GeneralFunctions::printGlyphiconCheck($check->succeded);
                        } else {
                            $icon2 = '<span class="glyphicon glyphicon-question-sign"></span> ';
                        }
                        $content .= $icon2 . ' ' . $check->getGroupName() . ' <i>' . $start . ' - ' . $eind . '</i></br>';
                        $countgroups++;
                    }
                }

                // Provide a shared InfoWindow to the marker
                $marker->attachInfoWindow(
                    new InfoWindow([
                        'content' => $content
                    ])
                );

                $link = Url::to(['time-trail-item/ajaxupdate'], true);
                $event = $this->getEvent($link, $item->time_trail_item_ID);

                $marker->addEvent($event);
                // Add marker to the map
                $this->addOverlay($marker);
                $countitems++;
            }
            $kleur++;
        }
    }

    public function setTrackPolygon($group = null)
    {
        if ($group) {
            $groupModel = $this->findGroupModel($group);
            $model = $groupModel->getTracks();
        } else {
            $model = Track::find()
                ->where([
                    'event_ID' => Yii::$app->user->identity->selected_event_ID,
                ])
                ->orderBy([
                    'timestamp' => SORT_ASC,
                    'create_time' => SORT_ASC
                ]);
        }
        if (!$model->exists()) {
            return;
        }
        $groups = Groups::getGroupOptionsForEvent();

        foreach ($groups as $group_ID => $group_name) {
            $this->addGroupTracks($model, $group_ID, $group_name);
        }
        if ($group === null) {
            $this->addOrganisationTracks($model);
        }
    }

    public function getEvent($link, $id)
    {
        $event = new Event([
            "trigger" => "dragend",
            "js" =>
            "
                function saveNewLocation () {
                    latitude = event.latLng.lat()
                    longitude = event.latLng.lng();
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
                    });
                };

                krajeeDialog.confirm('" . Yii::t('app', 'Are you sure you want to save new location?') . "', function (result) {
                    if (result) {
                        saveNewLocation();
                    } else {
                        location.reload();
                    }
                });
            "
        ]);
        return $event;
    }

    public function addGroupTracks(ActiveQuery $model, $group_ID, $group_name)
    {
        $coord = null;
        $modelGroup = $model;
        $modelGroup->where(['group_ID' => $group_ID]);

        $db = \yii\db\ActiveRecord::getDb();
        $models = $db->cache(function ($db) use ($modelGroup) {
            return $modelGroup->all();
        }, 3600, new TagDependency(['tags'=>['tracks_group_' . $group_ID]]));

        foreach ($models as $item) {
            if (!$item->getColorUserForEvent()) {
                continue;
            }
            $icon = new Symbol([
                'path' => SymbolPath::CIRCLE,
                'strokeColor' => $item->getColorUserForEvent(),
                'scale' => 4]);
            $coord = new LatLng(['lat' => $item->latitude, 'lng' => $item->longitude]);
            // Lets add a marker now
            $marker = new Marker([
                'position' => $coord,
                'title' => $group_name,
                'icon' => $icon,
            ]);
            // Add a shared info window
            $marker->attachInfoWindow(new InfoWindow([
                'content' => $item->getUserName() . ' (' . $group_name . '): ' . \Yii::$app->formatter->asDate($item->timestamp, 'php:d-M H:i')
            ]));

            $this->addOverlay($marker);
        }
    }

    public function addOrganisationTracks($model)
    {
        $organisations = DeelnemersEvent::getOrganisationCurrentGame();
        foreach ($organisations as $organisation) {
            $modelUser = $model;
            $modelUser->where(['user_ID' => $organisation->user_ID]);
            $modelUser->orderBy(['timestamp' => SORT_DESC]);
            $modelUser->limit(10);
            $db = \yii\db\ActiveRecord::getDb();
            $models = $db->cache(function ($db) use ($modelUser) {
                return $modelUser->all();
            }, 3600, new TagDependency(['tags'=>['tracks_user_' . $organisation->user_ID]]));

            foreach ($models as $item) {
                if (!$item->getColorUserForEvent()) {
                    continue;
                }
                $icon = new Symbol([
                    'path' => SymbolPath::CIRCLE,
                    'strokeColor' => $item->getColorUserForEvent(),
                    'scale' => 4]);
                $coord = new LatLng(['lat' => $item->latitude, 'lng' => $item->longitude]);
                // Lets add a marker now
                $marker = new Marker([
                    'position' => $coord,
                    'title' => $item->getUserName(),
                    'icon' => $icon,
                ]);
                // Add a shared info window
                $marker->attachInfoWindow(new InfoWindow([
                    'content' => $item->getUserName() . ': ' . \Yii::$app->formatter->asDate($item->timestamp, 'php:d-M H:i')
                ]));

                $this->addOverlay($marker);
            }
        }
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
}
