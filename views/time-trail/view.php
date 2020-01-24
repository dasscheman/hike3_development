<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use app\components\CustomAlertBlock;
use yii\bootstrap\Modal;

$this->registerJsFile('@web/js/countdown.js', [yii\web\JqueryAsset::className()]);
/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */

$this->title = Yii::t('app', 'Tijdritten');
$dataProvider = new ArrayDataProvider([
    'allModels' => $models,
]);

?>
<div class="tbl-time-trail-view-list">
    <div class="container text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="col-sm-3">
        </div>

        <div class="col-sm-6">
            <?php
            echo CustomAlertBlock::widget([
                'type' => CustomAlertBlock::TYPE_ALERT,
                'useSessionFlash' => true,
                'delay' => 20000,
            ]);
            ?>
            <?php
            echo ListView::widget([
                'summary' => FALSE,
                'pager' => FALSE,
                'dataProvider' => $dataProvider,
                'itemView' => '/time-trail/_list',
                'emptyText' => 'Er is nog geen time trail gestart.',
            ]);
            ?>
        </div>
        <div class="col-sm-3">
        </div>
    </div>
</div>
