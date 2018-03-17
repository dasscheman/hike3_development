<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-event-names-form">
    <?php

    if ($action == 'set_max_time') {
        $attributes['max_time'] = [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => 'kartik\time\TimePicker',
            'options' => [
                'attribute' => 'max_time',
                'pluginOptions' => [
                    'showSeconds' => FALSE,
                    'showMeridian' => FALSE,
                    'minuteStep' => 5,
                    'defaultTime' => '10:00'
                ]
            ]
        ];
    } else {
        $attributes['event_name'] = [
            'type' => Form::INPUT_TEXT,
            'options' => [
                'placeholder' => Yii::t('app', 'Geef je hike een herkenbare naam')
            ],
        ];

        $attributes['organisatie'] = [
            'type' => Form::INPUT_TEXT,
            'options' => [
                'placeholder' => Yii::t('app', 'De organisatie die de hike organiseert')
            ]
        ];

        $attributes['daterange'] = [
            'type' => Form::INPUT_WIDGET,
            'widgetClass' => 'kartik\daterange\DateRangePicker',
            'options' => [
                'startAttribute' => 'start_date',
                'endAttribute' => 'end_date',
                'pluginOptions' => [
                    'dateLimit' => [
                        'days' => 10
                    ],
                    'locale' => [
                        'format' => 'YYYY-MM-DD'
                        ],
                ]
            ]
        ];

        $attributes['website'] = [
            'type' => Form::INPUT_TEXT,
            'options' => [
                'placeholder' => Yii::t('app', 'Website organisatie')
            ]
        ];
    }

    $form = ActiveForm::begin([
        'id' => 'event-names-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 20],
        'action' => $model->isNewRecord ? ['event-names/create', 'action' => $action] : ['event-names/change-settings', 'event_ID' => $model->event_ID, 'action' => $action]]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => $attributes,
    ]);?>

    <?php echo Html::encode(Yii::t('app', 'You can change these fields later on.')); ?></br>

    <br>

    <div class="form-group">
        <?php
        if($model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Create'), ['name' => 'create']);
        } else {
            echo Html::submitButton(Yii::t('app', 'Save'),
            [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id' => $action,
                'data-method'=>'post',
                'data-pjax' => 'event-names-create-form',
                'name'=>'update'
            ]);

        }?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
