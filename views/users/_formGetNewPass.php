<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="users-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $attributes['username'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Username')
        ],
    ];
    
    $attributes['email'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Email')
        ]
    ];

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'formConfig' => ['labelSpan' => 20]]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => $attributes,
    ]);?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>