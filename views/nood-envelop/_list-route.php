<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use app\models\NoodEnvelop;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="view">
    <h3 class="max-titel-route-items-height">
        <b>
          <?php echo Html::encode($model->nood_envelop_name); ?>
        </b>
    </h3>
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
