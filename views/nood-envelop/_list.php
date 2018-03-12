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
            <?php echo Html::encode($model->nood_envelop_name); ?> </br>
        </h3>
        <p>
            <?php
             echo ButtonAjax::widget([
                'name' => Yii::t('app', 'Modify hint'),
                'route'=>['/nood-envelop/update', 'nood_envelop_ID' => $model->nood_envelop_ID],
                'modalId'=>'#main-modal',
                'modalContent'=>'#main-content-modal',
                'options'=>[
                    'class'=>'btn btn-xs btn-success',
                    'title'=> Yii::t('app', 'Modify hint'),
                    'disabled' => !Yii::$app->user->can('organisatie'),
                ]
            ]); ?>
        </p>

        <b>
        <?php echo Html::encode($model->getAttributeLabel('nood_envelop_volgorde')); ?>
        </b>
        <?php echo Html::encode($model->nood_envelop_volgorde); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('coordinaat')); ?>
        </b>
        <?php echo Html::encode($model->coordinaat); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('score')); ?>
        </b>
        <?php echo Html::encode($model->score); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('opmerkingen')); ?>
        </b>
        <?php echo Html::encode($model->opmerkingen); ?></br>

        </div>
    </div>
</div>
