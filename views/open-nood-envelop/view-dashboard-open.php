<?php

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-nood-envelop-view-dashboard-open">
    <h3> <?php echo Yii::t('app', 'Alle geopende hint') ?> </h3>
    <p>
        <?php
        Pjax::begin(['id' => 'nood-envelop-view-dashboard-open', 'enablePushState' => false]);
        ?>
    </p>
    <?php
        echo ListView::widget([
            'summary' => false,
            'pager' => [
                'prevPageLabel' => Yii::t('app', 'previous'),
                'nextPageLabel' => Yii::t('app', 'next'),
                'maxButtonCount' => 0,
                'options' => [
                   'class' => 'pagination pagination-sm',
                ],
            ],
            'dataProvider' => $model,
            'itemView' => '/open-nood-envelop/_list-dashboard-open',
            'emptyText' => Yii::t('app', 'No hints that are opened.'),
        ]);
    ?>
    <?php Pjax::end(); ?>
</div>
