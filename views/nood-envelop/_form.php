<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-nood-envelop-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['nood-envelop/create', 'route_ID' => $model->route_ID] : ['nood-envelop/update', 'nood_envelop_ID' => $model->nood_envelop_ID]]);

    echo $form->field($model, 'nood_envelop_name')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Recognizable name for this hint, visable by players.'
            )
        ]);
    echo $form->field($model, 'coordinaat')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Optional field for the coordinaat, only visable by players when they open the hint.'
            )
        ]);
    echo $form->field($model, 'opmerkingen')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'The actual hint to help the players, only visable by players when they open the hint.'
            )
        ]);
    echo $form->field($model, 'score')->textInput([
            'placeholder' => Yii::t(
                'app',
                'Penalty points for opening. Use positive integers.'
            )
        ]);
    echo $form->field($model, 'route_ID')->hiddenInput(['value'=> $model->route_ID])->label(false);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
