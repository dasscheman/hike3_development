<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">

	<?php echo Html::encode($model->user->getAttributeLabel('username')); ?>
	<?php echo Html::encode($model->user->voornaam . ' ' . $model->user->achternaam); ?> </br>
    <?php echo Html::encode($model->user->getAttributeLabel('email')); ?>
    <?php echo Html::encode($model->user->email); ?></br>
    <?php // echo Html::encode($model->user->getAttributeLabel('$coordinaat')); ?>
    <?php // echo Html::encode($model->coordinaat); ?></br>
    <?php // echo Html::encode($model->getAttributeLabel('score')); ?>
    <?php // echo Html::encode($model->score); ?></br>
    <?php // echo Html::encode($model->getAttributeLabel('$opmerkingen')); ?>
    <?php // echo Html::encode($model->opmerkingen); ?></br>

    </div>
</div>
