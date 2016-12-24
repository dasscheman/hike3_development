<?php

use yii\helpers\Html;
use kartik\builder\Form;
//use kartik\daterange\DateRangePicker;
//use kartik\file\FileInput;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\EventNames;
//use kartik\editable\Editable;
//use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-event-names-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php

    $attributes['event_name'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'disabled' => $action == 'change_status' && !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'Geef je hike een herkenbare naam')
        ],
    ];

    $attributes['organisatie'] = [
        'type' => Form::INPUT_TEXT,
        'options' => [
            'disabled' => $action == 'change_status' && !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'De organisatie die de hike organiseert')
        ]
    ];

    $attributes['daterange'] = [
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => 'kartik\daterange\DateRangePicker',
        'options' => [
            'disabled' => $action == 'change_status' && !$model->isNewRecord,
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
            'disabled' => $action == 'change_status' && !$model->isNewRecord,
            'placeholder' => Yii::t('app', 'Website organisatie')
        ]
    ];

    if ($action == 'change_status') {
        $attributes['status'] = [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => EventNames::getStatusOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'status'),
                'disabled' => $action == 'edit_settings',
            ],
        ];

        $attributes['active_day'] = [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => 'kartik\date\DatePicker',
            'options' => [
                'disabled' => $action == 'edit_settings',
                'value' => $model->active_day,
//                'type' => DatePicker::TYPE_COMPONENT_APPEND,
                'pluginOptions' => [
//                    'autoclose'=>true,
                    'format' => 'dd-M-yyyy',
//                    'minDate' => date('Y-m-d'),
                ]
            ]
        ];



//    'type' => DatePicker::TYPE_INPUT,
//    'value' => '23-Feb-1982',
//    'pluginOptions' => [
//        'autoclose'=>true,
//        'format' => 'dd-M-yyyy'
//    ]

        $attributes['max_time'] = [
            'type' => Form::INPUT_DROPDOWN_LIST,
            'items' => \app\models\EventNames::getStatusOptions(),
            'options' => [
                'placeholder' => Yii::t('app', 'status'),
                'disabled' => $action == 'edit_settings',
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