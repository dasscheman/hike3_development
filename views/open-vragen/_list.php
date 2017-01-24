<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <h3>
            <?php echo Html::encode($model->open_vragen_name); ?> </br>
        </h3>
        <p>
            <?php
             echo ButtonAjax::widget([
                'name' => Yii::t('app', 'Edit question'),
                'route' => ['/open-vragen/update', 'id' => $model->open_vragen_ID],
                'modalId' => '#main-modal',
                'modalContent'=>'#main-content-modal',
                'options' => [
                    'class' => 'btn btn-xs btn-success',
                    'title' => 'Button for create application',
                ]
            ]); ?>

        </p>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('vraag_volgorde')); ?>
        </b>
        <?php echo Html::encode($model->vraag_volgorde); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>
        </b>
        <?php echo Html::encode($model->omschrijving); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('vraag')); ?>
        </b>
        <?php echo Html::encode($model->vraag); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('goede_antwoord')); ?>
        </b>
        <?php echo Html::encode($model->goede_antwoord); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('score')); ?>
        </b>
        <?php echo Html::encode($model->score); ?></br>

        </div>
    </div>
</div>
