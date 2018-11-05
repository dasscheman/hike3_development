<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use app\components\CustomAlertBlock;
use yii\bootstrap\Modal;

$add_time = 0;
$factor = 1;
$alternate_time = array_key_exists(Yii::$app->user->identity->selected_event_ID, Yii::$app->params["alternate_time"]);

if($alternate_time) {
    $add_time = Yii::$app->params["alternate_time"][Yii::$app->user->identity->selected_event_ID]['add'] * 1000;
    $factor = Yii::$app->params["alternate_time"][Yii::$app->user->identity->selected_event_ID]['factor'];
}
$this->registerJsVar ( 'alternate_time', $alternate_time, \yii\web\View::POS_HEAD );
$this->registerJsVar ( 'add_time', $add_time, \yii\web\View::POS_HEAD );
$this->registerJsVar ( 'factor', $factor, \yii\web\View::POS_HEAD );
$this->registerJsFile('@web/js/countdown.js', [yii\web\JqueryAsset::className()]);
/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */

$this->title = Yii::t('app', 'Time Trails');
$dataProvider = new ArrayDataProvider([
    'allModels' => $models,
]);
// dd(Yii::$app->user->identity->   selected_event_ID);
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
