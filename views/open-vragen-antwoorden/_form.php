<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragenAntwoorden */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-vragen-antwoorden-form">

    <?php
    Pjax::begin(['id' => 'open-vragen-antwoorden-form-' . $model->open_vragen_ID, 'enablePushState' => false]);
    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]); $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'antwoord_spelers')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?php

        echo Html::a(
            Yii::t('app', 'Save'),
            ['/open-vragen-antwoorden/beantwoorden', 'id' => $modelVraag->open_vragen_ID],
            ['class' => 'btn btn-primary'],
            ['data-pjax' => 'open-vragen-antwoorden-list-' . $modelVraag->open_vragen_ID]
        );

        echo Html::a(
            Yii::t('app', 'Cancel'),
            ['/open-vragen-antwoorden/cancel-beantwoording', 'id' => $modelVraag->open_vragen_ID],
            ['class' => 'btn btn-danger'],
            ['data-pjax' => 'open-vragen-antwoorden-list-' . $modelVraag->open_vragen_ID]
        ); ?>
    </div>
    <?php ActiveForm::end();
    Pjax::end(); ?>
  
</div>
