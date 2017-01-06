<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">

    <p>
        <?php
         echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Edit question'),
            'route' => ['/open-vragen/update', 'id' => $model->open_vragen_ID],
            'modalId' => '#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-success',
                'title' => 'Button for create application',
            ]
        ]); ?>

    </p>
	<?php echo Html::encode($model->getAttributeLabel('open_vragen_name')); ?>
	<?php echo Html::encode($model->open_vragen_name); ?> </br>
    <?php echo Html::encode($model->getAttributeLabel('vraag_volgorde')); ?>
    <?php echo Html::encode($model->vraag_volgorde); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>
    <?php echo Html::encode($model->omschrijving); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('vraag')); ?>
    <?php echo Html::encode($model->vraag); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('goede_antwoord')); ?>
    <?php echo Html::encode($model->goede_antwoord); ?></br>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>
    <?php echo Html::encode($model->score); ?></br>
        
    </div>
</div>