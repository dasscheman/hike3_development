<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\components\CustomAlertBlock;
use kartik\dialog\Dialog;
use app\models\OpenMap;
use app\models\DeelnemersEvent;
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
        if (Yii::$app->user->identity->getRolUserForEvent() !== DeelnemersEvent::ROL_organisatie) {
            ?> <br><br> <?php
            break;
        }
        if ($routeItem->route_ID !== $routeModel->route_ID) {
            echo Html::a(
                $routeItem->route_name,
                [
                'view',
                'route_ID' => $routeItem->route_ID,
                ],
                ['class' => 'btn-lg route-buttons']
            );
        } else {
            OpenMap::setCookieIndexRoute($routeModel->route_ID);
            echo Html::label(
                $routeItem->route_name,
                [
                'view',
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
</div>
