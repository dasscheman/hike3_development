<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">

    <p>
        <?php
         echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Edit station checkin'),
            'route' => ['/post-passage/update', 'id' => $model->posten_passage_ID],
            'modalId' => '#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-success',
                'title' => 'Button for create application',
            ]
        ]); ?>

    </p>
    
	<?php echo Html::encode($model->getAttributeLabel('post_ID')); ?>
	<?php echo Html::encode($model->post_ID); ?> </br>
    <?php echo Html::encode($model->getAttributeLabel('group_ID')); ?>
    <?php echo Html::encode($model->group_ID); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('gepasseerd')); ?>
    <?php echo Html::encode($model->gepasseerd); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('binnenkomst')); ?>
    <?php echo Html::encode($model->binnenkomst); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('vertrek')); ?>
    <?php echo Html::encode($model->vertrek); ?></br>
        
    </div>
</div>