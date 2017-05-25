<?php

use yii\helpers\Html;
use kartik\builder\Form;
//use kartik\daterange\DateRangePicker;
//use kartik\file\FileInput;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\EventNames;
use yii\helpers\Url;
use kartik\widgets\DepDrop;
//use kartik\editable\Editable;
//use yii\bootstrap\Modal;

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
                    'minDate' => date('d-m-Y'),
                    "dateLimit" => [
                        'days' => 10
                    ],
                    'locale' => [
                        'format' => 'DD-MM-YYYY',
                        'separator' => Yii::t('app', ' t/m ')],
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
        'action' => $model->isNewRecord ? ['event-names/create', 'action' => $action] : ['event-names/update', 'event_ID' => $model->event_ID, 'action' => $action]]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 4,
        'attributes' => $attributes,
    ]);?>

    <?php echo Html::encode(Yii::t('app', 'You can change these fields later on.')); ?></br>

    <br>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ?
            Yii::t('app', 'Create') :
            Yii::t('app', 'Save'),
            [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id' => $action,
                'data-method'=>'post',
                'data-pjax' => 'event-names-create-form'
            ]) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
