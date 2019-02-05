<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\components\CustomAlertBlock;
use kartik\dialog\Dialog;
use app\models\OpenMap;
use kartik\popover\PopoverX;
use yii\web\View;
use RDConverter\RDConverter;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$converter = new RDConverter;

$this->title = Yii::t('app', 'Kaart voor: ') . $routeModel->route_name;
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
                    echo round($converter->gps2X($marker->getLatLng()->lat, $marker->getLatLng()->lng)); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Latitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Latitude van de pointer'),
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
                    <h3 id="longitude"><?php
                    echo round($converter->gps2Y($marker->getLatLng()->lat, $marker->getLatLng()->lng)); ?></h3>
                </div>
                <div class="map-left">
                    <?php
                    echo PopoverX::widget([
                        'header' => Yii::t('app', 'Longitude'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Longitude van de pointer'),
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
                                        'label' => Yii::t('app', 'Post toevoegen'),
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
                        'content' => Yii::t('app', 'Voor elke dag kun je post toevoegen. '
                            . 'Groepen krijgen de punten als ze ingechecked worden op een post.'
                            . 'Vergeet niet een start- en eind-post voor elke dag aan te maken.'
                            . 'Ps. posten zijn per dag en niet per routeonderdeel. Posten zijn niet voor de introductie'),
                        'toggleButton' => [
                            'label'=> Html::img('@web/images/map_icons/star-3.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Posten') ?></h4>
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
                                    'label' => Yii::t('app', 'Stillepost toevoegen'),
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
                        'header' => Yii::t('app', 'Stilleposten'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Stille posten zijn qr codes die de deelnemers zelf kunnen scannen'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/qr-code.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Stillepost') ?></h4>
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
                                    'label' => Yii::t('app', 'Hint toevoegen'),
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
                        'content' => Yii::t('app', 'Met hints kun je deelnemers een aanwijzing geven voor een puzzel, '
                            . 'of je kunt een aanwijzing geven waar een volgend route onderdeel begint.'),
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
                                    'label' => Yii::t('app', 'Vraag toevoegen'),
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
                        'header' => Yii::t('app', 'Vragen'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Met extra vragen kun je controleren of deelnemers juis lopen.'),
                        'toggleButton' => [
                        'label'=> Html::img('@web/images/map_icons/notvisited.png'),
                            'class' => 'map-popover'],
                    ]);?>
                </div>
                <h4 class="left"><?php echo Yii::t('app', 'Vragen') ?></h4>
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
                                    'label' => Yii::t('app', 'Tijdrit toevoegen'),
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
                        'header' => Yii::t('app', 'Tijdritten'),
                        'type' => PopoverX::TYPE_INFO,
                        'placement' => PopoverX::ALIGN_RIGHT,
                        'content' => Yii::t('app', 'Voor een hike kun je 6 tijdritten maken. Deze tijdritten zijn onafhankelijk van dagen en routes.'
                            . 'Als en groep het eerste item scant, krijgt de group een aanwijzing waar ze de volgende qr kunnen vinden.'
                            . 'Maar de groep heeft een tijdslimiet, wanneer ze binnen de tijd de volgende qr scannen krijgen ze punten.'
                            . 'Wanneer ze te laat zijn, moeten ze nog steeds de qr scannen om de aanwijzing te krijgen voor de volgende qr.'),
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
                                        'label' => Yii::t('app', 'Item voor ' . $timeTrail->time_trail_name),
                                        'class' => 'btn btn btn-success padding-right',
                                        'disabled' => !Yii::$app->user->can('organisatie'),
                                    ],
                                ]
                            );

                            echo $this->render('/time-trail-item/create', [
                                'model' => $timeTrailItemModel,
                                'time_trail_name' => $timeTrail->time_trail_name]);
                            Modal::end(); ?>
                        </h3>
                    </div>
                    <div class="map-left">
                        <?php
                        $kleuren = new OpenMap;
                        $numberItems = $timeTrail->getTimeTrailItems()->count();
                        echo PopoverX::widget([
                            'header' => Yii::t('app', 'Tijdrit') . ' ' . $timeTrail->time_trail_name,
                            'type' => PopoverX::TYPE_INFO,
                            'placement' => PopoverX::ALIGN_RIGHT,
                            'content' => Yii::t('app', 'Dit is een tijdrit waar items aan toegevoegd kan worden.'),
                            'toggleButton' => [
                                'label'=> Html::img('@web/images/map_icons/marker/' . $kleuren->kleuren[fmod($kleur, 5)] . '_' . $numberItems . '.png'),
                                'class' => 'map-popover'],
                        ]); ?>
                    </div>
                    <h4 class="left"><?php echo Yii::t('app', 'Tijdrit') . ' ' . $timeTrail->time_trail_name ?></h4>
                </div>
            </div>
            <?php
            $kleur++;
        }
        ?>
    </div>
</div>
</div>
