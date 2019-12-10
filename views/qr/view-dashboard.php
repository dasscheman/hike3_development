<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-qr-view">

    <h1><?= Html::encode(Yii::t('app', 'Stilleposten')) ?></h1>

    <?php
        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $model,
            'itemView' => '/qr/_list',
            'emptyText' => Yii::t('app', 'There are no silent stations for this route section'),
        ]);
    ?>
</div>
