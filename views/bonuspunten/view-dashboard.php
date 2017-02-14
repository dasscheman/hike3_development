<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-bonuspunte-view-dashboard">
    <h3> <?php echo Yii::t('app', 'Bonus') ?> </h3>
    <p>
        <?php
        Pjax::begin(['id' => 'bonuspunten-view-dashboard', 'enablePushState' => false]);
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
            'itemView' => '/bonuspunten/_list-dashboard',
            'emptyText' => Yii::t('app', 'No bonuspoints.'),
        ]);
    ?>
    <?php Pjax::end(); ?>
</div>
