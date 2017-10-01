<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */

?>
<div class="tbl-time-trail-view-dashboard">
    <h3> <?php echo Yii::t('app', 'Checked time trails') ?> </h3>
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