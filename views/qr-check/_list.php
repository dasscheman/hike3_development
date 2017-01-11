<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <?php
        if ($model->isSilentStationCkeckedByGroup()) { ?>
            <h3>
            <?php echo Html::encode($model->qr_name); ?> 
            </h3>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
            </b>
            <?php echo Html::encode($model->score); ?></br>
            <b>
            <?php echo Html::encode($model->getSilentStationCkeckedByGroup()->getAttributeLabel('create_user_ID')); ?>:
            </b>
            <?php echo Html::encode($model->getSilentStationCkeckedByGroup()->create_user_ID); ?></br>
            <b>
            <?php echo Html::encode($model->getSilentStationCkeckedByGroup()->getAttributeLabel('create_time')); ?>:
            </b>
            <?php echo Html::encode($model->getSilentStationCkeckedByGroup()->create_time);
        }?>

        </div>
    </div>
</div>