<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <h3>
            <?php echo Html::encode($model->omschrijving); ?>
            </h3>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
            </b>
            <?php echo Html::encode($model->score); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('create_user_ID')); ?>:
            </b>
            <?php echo Html::encode($model->createUser->username); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('create_time')); ?>:
            </b>
            <?php echo Html::encode($model->create_time); ?></br>

        </div>
    </div>
</div>
