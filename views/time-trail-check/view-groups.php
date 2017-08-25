<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */

$this->title = Yii::t('app', 'Time Trails');
?>
<div class="tbl-time-trail-check-view-groups">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $groups,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/time-trail-check/_list-groups',
            'viewParams' => ['time_trail_item_id' => $time_trail_item_id],
            'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
        ]);
    ?>
</div>
