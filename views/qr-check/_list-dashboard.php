<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="view">
    <h4>
        <?php echo Html::encode($model->qr->qr_name); ?>
    </h4>
    <b>
    <?php echo Html::encode($model->qr->getAttributeLabel('score')); ?>:
    </b>
    <?php echo Html::encode($model->qr->score); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('create_user_ID')); ?>:
    </b>
    <?php echo Html::encode($model->createUser->username); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('create_time')); ?>:
    </b>
    <?php echo Html::encode($model->create_time);?>
</div>