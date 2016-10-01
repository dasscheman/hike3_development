<?php

use yii\helpers\Html;
use kartik\builder\Form;
//use kartik\daterange\DateRangePicker;
use kartik\file\FileInput;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
//use kartik\editable\Editable;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\TblEventNames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-event-names-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $attributes['event_name'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            //'disabled' => !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'Geef je hike een herkenbare naam')
        ],
    ];

    $attributes['organisatie'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            //'disabled' => !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'De organisatie die de hike organiseert')
        ]
    ];

    $attributes['daterange'] = [
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => 'kartik\daterange\DateRangePicker',
        'options' => [
            //'disabled' => !$model->isNewRecord,
            'startAttribute' => 'start_date',
            'endAttribute' => 'end_date',
            'pluginOptions' => [
                'minDate' => date('Y-m-d'),
                "dateLimit" => [
                    'days' => 10
                ],
                'locale' => [
                    'format' => 'YYYY-MM-DD',
                    'separator' => Yii::t('app', ' t/m ')],
            ]
        ]
    ];

    $attributes['website'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            //'disabled' => !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'Website organisatie')
        ]
    ];

    if (!$model->isNewRecord) {
        $attributes['status'] = [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => \app\models\EventNames::getStatusOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'status'),
            //'disabled' => !$model->isNewRecord, 
            ],
        ];

        $attributes['active_day'] = [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => 'kartik\daterange\DateRangePicker',
            'options' => [
                //'disabled' => !$model->isNewRecord,
                'pluginOptions' => [
                    'singleDatePicker' => true,
                    'autoclose' => true,
                    'minDate' => $model->start_date,
                    'maxDate' => $model->end_date,
                    'locale' => [
                        'format' => 'YYYY-MM-DD',
                    ]
                ]
            ]
        ];

        $attributes['max_time'] = [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => \app\models\EventNames::getStatusOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'status'),
                //'disabled' => !$model->isNewRecord,
            ],
        ];
    }

    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'formConfig' => ['labelSpan' => 20]]);
    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => $attributes,
    ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>