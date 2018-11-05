<?php

use yii\helpers\Html;
use app\components\CustomAlertBlock;
use kartik\dialog\Dialog;
use app\models\OpenMap;
use kartik\popover\PopoverX;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

echo Dialog::widget();
?>
<div class="container-map">
    <div class="map-index">
        <?php
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
        <div>
            <?php
                foreach ($tracks as $key => $track){
                    echo Html::a(
                        Yii::t('app', 'Delete ') . $track->name,
                        ['delete-track', 'track_name' => $track->name],
                        [
                            'class' => 'btn btn-lg',
                            'data-method' => 'POST',
                            'data-params' => [
                                'track_name' => $track->name,
                            ],
                        ]);
                }
                if($wp_exists) {
                    echo Html::a(
                        Yii::t('app', 'Delete all waypoints'),
                        ['delete-waypoints'],
                        [
                            'class' => 'btn btn-lg',
                        ]
                    );
                }
            ?>
        </div>
    </div>
</div>
