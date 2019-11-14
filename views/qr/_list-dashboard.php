<?php
use yii\helpers\Html;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <h3 class="max-titel-route-items-height">
        <b>
            <?php echo Html::encode($model->qr_name); ?>
        </b>
    </h3>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>
    </b>
    <?php echo Html::encode($model->score); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('message')); ?>
    </b>
    <?php echo Html::encode($model->message); ?></br>
</div>
