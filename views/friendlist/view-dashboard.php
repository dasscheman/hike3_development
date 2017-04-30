<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-friendlist-request-view-dashboard">
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
            'showOnEmpty' => TRUE,
            'dataProvider' => $model,
            'itemView' => '/friendlist/_list-dashboard',
            'emptyText' => Yii::t('app', 'Currently no friend requests.'),
        ]);
    ?>
</div>
