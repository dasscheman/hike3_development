<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">

	<?php echo Html::encode($model->getAttributeLabel('post_ID')); ?>
	<?php echo Html::encode($model->post_ID); ?> </br>
    <?php echo Html::encode($model->getAttributeLabel('group_ID')); ?>
    <?php echo Html::encode($model->group_ID); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>
    <?php echo Html::encode($model->omschrijving); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>
    <?php echo Html::encode($model->score); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('create_user_ID')); ?>
    <?php echo Html::encode($model->create_user_ID); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('create_time')); ?>
    <?php echo Html::encode($model->create_time); ?></br>
        
    </div>
</div>