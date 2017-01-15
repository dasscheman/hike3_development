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
    $attributes['username'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Username')
        ],
    ];

    $attributes['voornaam'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'First name')
        ],
    ];
    
    $attributes['achternaam'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Surname')
        ],
    ];
    
    $attributes['organisatie'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Belongs to organisation')
        ]
    ];

    
    $attributes['email'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'placeholder' => Yii::t('app', 'Email')
        ]
    ];
    
    $attributes['birthdate'] = [
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => 'kartik\date\DatePicker', 
        'options' => [
            'pluginOptions' => [
                'value' => date('d-M-Y'),
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]
    ];

    if ($model->isNewRecord) {
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
    }
    $form = ActiveForm::begin([
        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 20],
        'action' => $model->isNewRecord ? ['users/create'] : ['users/update'],
    ]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 3,
        'attributes' => $attributes,
    ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>