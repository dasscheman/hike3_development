<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-qr-check-view-dashboard">
    <h3> <?php echo Yii::t('app', 'Alle stillepost') ?> </h3>
    <p>
        <?php
        Pjax::begin(['id' => 'qr-check-view-dashboard', 'enablePushState' => false]);
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
            'itemView' => '/qr-check/_list-dashboard',
            'emptyText' => Yii::t('app', 'Geen stilleposten gescand.'),
        ]);
    ?>
    <?php Pjax::end(); ?>
</div>
