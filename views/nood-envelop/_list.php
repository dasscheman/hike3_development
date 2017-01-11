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
            'name'=>Yii::t('app', 'Modify hint'),
            'route'=>['/nood-envelop/update', 'id' => $model->nood_envelop_ID],
            'modalId'=>'#main-modal',
            'modalContent'=>'#main-content-modal',
            'options'=>[
                'class'=>'btn btn-success',
                'title'=>'Button for create application',
            ]
        ]); ?>


        <?php
         echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Delete hint'),
            'route'=>['/nood-envelop/delete', 'id' => $model->nood_envelop_ID],
                'modalId'=>'#main-modal',
                'modalContent'=>'#main-content-modal',
            'options'=>[
                'class'=>'btn btn-danger',
                'title'=>Yii::t('app', 'Remove this hint'),
//                'data' => [
//                    'confirm' => Yii::t('app', 'Are you sure you want to delete this Hint?'),
//                    'method' => 'post',
//
//                ],
            ]
        ]); ?>

    </p>
	<?php echo Html::encode($model->getAttributeLabel('nood_envelop_name')); ?>
	<?php echo Html::encode($model->nood_envelop_name); ?> </br>
    <?php echo Html::encode($model->getAttributeLabel('nood_envelop_volgorde')); ?>
    <?php echo Html::encode($model->nood_envelop_volgorde); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('$coordinaat')); ?>
    <?php echo Html::encode($model->coordinaat); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>
    <?php echo Html::encode($model->score); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('$opmerkingen')); ?>
    <?php echo Html::encode($model->opmerkingen); ?></br>
        
    </div>
</div>