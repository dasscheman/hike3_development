<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="users-form">
    <?php
    
    $attributes['password'] = [
        'type' => Form::INPUT_PASSWORD,
        'options' => [
            'placeholder' => Yii::t('app', 'Password'),
            'value'=>'',
        ]
    ];

    $attributes['password_repeat'] = [
        'type' => Form::INPUT_PASSWORD,
        'options' => [
            'placeholder' => Yii::t('app', 'Repeat password')
        ]
    ];

    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 20],
        'action' => ['users/change-password'],
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
        'attributes' => $attributes,
    ]);?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>