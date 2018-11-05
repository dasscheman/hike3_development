<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\components\CustomAlertBlock;
use kartik\dialog\Dialog;
use app\models\OpenMap;
use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = Yii::t('app', 'Map for route: ') . $routeModel->route_name;
    echo Dialog::widget();
?>
<div class="container-map">
<div class="map-index">
    <?php
    foreach ($routeDataProvider->getModels() as $routeItem) {
        if ($routeItem->route_ID !== $routeModel->route_ID) {
            echo Html::a(
                $routeItem->route_name,
                [
                'edit',
                'route_ID' => $routeItem->route_ID,
                ],
                ['class' => 'btn-lg route-buttons']
            );
        } else {
            OpenMap::setCookieIndexRoute($routeModel->route_ID);
            echo Html::label(
                $routeItem->route_name,
                [
                    'edit',
                    'route_ID' => $routeItem->route_ID,
                ],
                ['class' => 'btn-lg route-buttons']
            );
        }
    }
    echo CustomAlertBlock::widget([
        'type' => CustomAlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => false,
        'options' => [
            'class' => 'map-alert']
        ]);
    ?>
    <div class="map">
        <?php
        $screen = Yii::$app->getRequest()->getCookies()->getValue('screen_size') - 520;
        if ($screen === null ||
            $screen < 250) {
            $screen = 250;
        }
        // Display the map -finally :)
        echo $map->widget(['options' => ['style' => 'height: ' . $screen . 'px']]);
        ?>
    </div>
    <div class="legenda">
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3 id="latitude"><?php
                    echo round($marker->getLatLng()->lat, 5); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Latitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Coordinates of the pointer'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/map.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left">Latitude</h4>
            </div>
        </div>

        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3 id="longitude"><?php echo round($marker->getLatLng()->lng, 6); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Longitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Coordinates of the pointer'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/map.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left">Longitude</h4>
            </div>
        </div>
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3 id="latitudetest" class="latitude"><?php
                    echo round($marker->getLatLng()->lat, 5); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Latitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Coordinates of the pointer'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/map.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left">Latitude</h4>
            </div>
        </div>

        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3 id="longitudetest" class="longitude"><?php echo round($marker->getLatLng()->lng, 6); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Longitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Coordinates of the pointer'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/map.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left">Longitude</h4>
            </div>
        </div>
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3>
                        <?php
                        if ($routeModel->day_date !== null) {
                            Modal::begin(
                                [
                                    'id' => 'modalCreatePost',
                                    'toggleButton' => [
                                        'label' => Yii::t('app', 'Add station'),
                                        'class' => 'btn btn btn-success padding-right',
                                        'disabled' => !Yii::$app->user->can('organisatie'),
                                    ],
                                ]
                            );

                            echo $this->render('/posten/create', [
                                'model' => $postenModel
                            ]);
                            Modal::end();
                        }
                        ?>

                        </h3>
                </div>
                <div class="map-left">
                    <?php
                    $options = ['class' => ['awesome-marker-icon-green', 'awesome-marker', 'leaflet-zoom-animated', 'leaflet-interactive', 'leaflet-marker-draggable']];
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Stations'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'For each day you can make a station. '
                            . 'Groups get the score when they arrive on the station.'
                            . 'Don\'t forget to create a start and a finish station for each day.'
                            . 'Note, stations are per day and not per route item. And stations are not available during the introduction'),
                        'toggleButton' => [
                            'label'=> Html::img('@web/images/map_icons/star-3.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Stations') ?></h4>
            </div>
        </div>
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3>
                        <?php
                        Modal::begin(
                            [
                                'id' => 'modalCreateQr',
                                'toggleButton' => [
                                    'label' => Yii::t('app', 'Add silent station'),
                                    'class' => 'btn btn btn-success padding-right',
                                    'disabled' => !Yii::$app->user->can('organisatie'),
                                ],
                            ]
                        );

                        echo $this->render('/qr/create', [
                            'model' => $qrModel]);
                        Modal::end();
                        ?>

                        </h3>
                </div>
                <div class="map-left">

                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Silent stations'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'This are stations where groups can scan qr codes'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/qr-code.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Silent station') ?></h4>
            </div>
        </div>
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3>
                        <?php
                        Modal::begin(
                            [
                                'id' => 'modalCreateHint',
                                'toggleButton' => [
                                    'label' => Yii::t('app', 'Add hint'),
                                    'class' => 'btn btn btn-success padding-right',
                                    'disabled' => !Yii::$app->user->can('organisatie'),
                                ],
                            ]
                        );

                        echo $this->render('/nood-envelop/create', [
                            'model' => $hintModel]);
                        Modal::end();

                        ?>
                        </h3>
                </div>
                <div class="map-left">

                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Hints'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Hints can give a clue how to solve a puzzle or where a new route item starts.'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/postal.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Hints') ?></h4>
            </div>
        </div>
        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3>

                        <?php
                        Modal::begin(
                            [
                                'id' => 'modalCreateVraag',
                                'toggleButton' => [
                                    'label' => Yii::t('app', 'Add question'),
                                    'class' => 'btn btn btn-success padding-right',
                                    'disabled' => !Yii::$app->user->can('organisatie'),
                                ],
                            ]
                        );

                        echo $this->render('/open-vragen/create', [
                            'model' => $vragenModel]);
                        Modal::end();
                        ?>
                        </h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Questions'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'With questions you can check if groups are on the right track.'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/notvisited.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Questions') ?></h4>
            </div>
        </div>

        <div class="kwart">
            <div class="map-button">
                <div class="map-right">
                    <h3>

                        <?php
                        Modal::begin(
                            [
                                'id' => 'modalCreateTimeTrail',
                                'toggleButton' => [
                                    'label' => Yii::t('app', 'Add time trail'),
                                    'class' => 'btn btn btn-success padding-right',
                                    'disabled' => !Yii::$app->user->can('organisatie'),
                                ],
                            ]
                        );

                        echo $this->render('/time-trail/create', [
                            'model' => $timeTrailModel]);
                        Modal::end();


                        ?>
                        </h3>
                </div>
                <div class="map-left">
                    <?php

                    $kleuren = new OpenMap();

                    for ($x = 0; $x <= 4; $x++) {
                        echo Html::img('@web/images/map_icons/marker/' . $kleuren->kleuren[$x] . '_leeg_marker.png', ['class' => 'time-trail-images']);
                    }

                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Time trails'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'For an hike you can make 6 time trails. These time trails are independent of days and routes.'
                            . 'When a group scans the first item of a time trail, the get a clue where they can find the next item. '
                            . 'But they have limited time to reach the next item. When they are in time, they get points. When they are to late '
                            . 'they still have to scan the item, to get the clue for the next item.'),
                        'toggleButton' => [
                            'label'=> Html::img('@web/images/map_icons/marker/' . $kleuren->kleuren[5] . '_leeg_marker.png'),
                            'class' => 'map-popover time-trial-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Time trail') ?></h4>
            </div>
        </div>

        <?php
        $kleur = 0;
        foreach ($timeTrailData as $timeTrail) {
            ?>
            <div class="kwart">
                <div class="map-button">
                    <div class="map-right">
                        <h3> <?php
                            $timeTrailItemModel->time_trail_ID = $timeTrail->time_trail_ID;
                            Modal::begin(
                                [
                                    'id' => 'modalCreateTimeTrail' . $timeTrail->time_trail_ID,
                                    'toggleButton' => [
                                        'label' => Yii::t('app', 'Add item to ' . $timeTrail->time_trail_name),
                                        'class' => 'btn btn btn-success padding-right',
                                        'disabled' => !Yii::$app->user->can('organisatie'),
                                    ],
                                ]
                            );

                            echo $this->render('/time-trail-item/create', [
                                'model' => $timeTrailItemModel]);
                            Modal::end(); ?>
                        </h3>
                    </div>
                    <div class="map-left">
                        <?php
                        $kleuren = new OpenMap;
                        $numberItems = $timeTrail->getTimeTrailItems()->count();
            echo PopoverX::widget([
                            'header' => Yii::t('app', 'Time trail '). $timeTrail->time_trail_name,
                            'type' => PopoverX::TYPE_INFO,
                            'placement' => PopoverX::ALIGN_RIGHT,
                            'content' => Yii::t('app', 'This is one time trail, the numbers indicates the order of the items.'),
                            'toggleButton' => [
                                'label'=> Html::img('@web/images/map_icons/marker/' . $kleuren->kleuren[fmod($kleur, 5)] . '_' . $numberItems . '.png'),
                                'class' => 'map-popover'],
                        ]); ?>
                    </div>
                    <h4 class="left"><?php echo Yii::t('app', 'Time trail ') . $timeTrail->time_trail_name ?></h4>
                </div>
            </div>
            <?php
            $kleur++;
        }
        ?>
    </div>
</div>
</div>
