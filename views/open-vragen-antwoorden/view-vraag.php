<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-vragen-view-vraag">
    <h3> <?php echo Yii::t('app', 'Question') ?> </h3>
    <p>
        <?php
        // this is needed for the pagination.
        Pjax::begin(['id' => 'open-vragen-antwoorden-view-vraag', 'enablePushState' => false]);
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
            'itemView' => '/open-vragen-antwoorden/_list-questions',
            'emptyText' => Yii::t('app', 'No question to be answered.'),
        ]);
    ?>
    <?php Pjax::end(); ?>
</div>
