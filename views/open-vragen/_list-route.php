<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use app\models\OpenVragen;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <p>
        <h3>
            <?php echo Html::encode($model->open_vragen_name); ?> </br>
        </h3>
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
