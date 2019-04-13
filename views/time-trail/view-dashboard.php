<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */
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

?>
<div class="tbl-time-trail-view-dashboard">
    <h3> <?php echo Yii::t('app', 'Gescande tijdritten') ?> </h3>
    <p>
        <?php
        // this is needed for the pagination.
        Pjax::begin(['id' => 'time-trail-view-dashboard-list', 'enablePushState' => false]);
        ?>
    </p>
    <?php
        echo ListView::widget([
            'summary' => FALSE,
            'pager' => [
                'prevPageLabel' => Yii::t('app', 'previous'),
                'nextPageLabel' => Yii::t('app', 'next'),
                'maxButtonCount' => 0,
                'options' => [
                   'class' => 'pagination pagination-sm',
                ],
            ],
            'dataProvider' => $model,
            'itemView' => '/time-trail/_list-dashboard',
            'emptyText' => 'Er is nog geen time trail gestart.',
        ]);
    ?>
    <?php  Pjax::end(); ?>
</div>
