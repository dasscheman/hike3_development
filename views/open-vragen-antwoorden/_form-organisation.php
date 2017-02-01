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
    Pjax::begin([
        'id' => 'open-vragen-antwoorden-form-' . $model->open_vragen_antwoorden_ID,
        'enablePushState' => FALSE,
        // 'clientOptions' => ['method' => 'POST']
    ]);

    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]);

    $form = ActiveForm::begin([
        'options'=>[
            'data-pjax'=>TRUE,
        ]
    ]);
?>
    <h3>
    <?php echo Html::encode($model->openVragen->open_vragen_name); ?>
    </h3>
    <b>
    <?php echo Html::encode($model->openVragen->getAttributeLabel('vraag')); ?>:
    </b>
    <?php echo Html::encode($model->openVragen->vraag); ?></br>

    <b>
    <?php echo Html::encode($model->openVragen->getAttributeLabel('goede_antwoord')); ?>:
    </b>
    <?php echo Html::encode($model->openVragen->goede_antwoord); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('antwoord_spelers')); ?>:
    </b>
    <?php echo Html::encode($model->antwoord_spelers); ?></br>
    <?php
    echo $form->field($model, 'correct')->checkbox([]);
    echo $form->field($model, 'checked')->checkbox([])  ?>

    <div class="form-group">
        <?php
        echo Html::a(
            Yii::t('app', 'Save'),
            [
                '/open-vragen-antwoorden/update-organisatie',
                'id' => $model->open_vragen_antwoorden_ID
            ],
            [
                'class' => 'btn btn-xs btn-primary',
                'data-method'=>'post',
                'data-pjax' => 'open-vragen-antwoorden-form-' . $model->open_vragen_antwoorden_ID
            ]
        );

        echo Html::a(
            Yii::t('app', 'Cancel'),
            [
                '/open-vragen-antwoorden/cancel',
                'id' => $model->open_vragen_antwoorden_ID],
            [
                'class' => 'btn btn-xs btn-danger',
                'data-method'=>'post',
                'data-pjax' => 'open-vragen-antwoorden-form-' . $model->open_vragen_antwoorden_ID
            ]
        ); ?>
    </div>
    <?php
    ActiveForm::end();
    Pjax::end(); ?>

</div>
