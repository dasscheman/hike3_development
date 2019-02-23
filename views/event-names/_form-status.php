npo<?php

use yii\helpers\Html;
use kartik\builder\Form;
use kartik\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsVar ( 'posten', $model->postens, \yii\web\View::POS_HEAD );
$this->registerJsVar ( 'postpassages', $model->postPassages, \yii\web\View::POS_HEAD );
?>

<div class="tbl-event-names-form">
    <?php

    $attributes['status'] = [
        'type' => Form::INPUT_DROPDOWN_LIST,
        'items' => $model->getStatusOptions(),
        'options' => [
            'placeholder' => Yii::t('app', 'Status van de hike')
        ],
    ];

    $attributes['active_day'] = [
        'type' => Form::INPUT_DROPDOWN_LIST,
        'items' => $model->getDatesAvailable(false),
        'options' => [
            'prompt' => Yii::t('app', 'Selecteer active dag'),
            'placeholder' => Yii::t('app', 'Active dag')
        ]
    ];

    $attributes['max_time'] = [
        'type' => Form::INPUT_TEXT,
        // 'value' => '01/04/2005 08:17',
        'attribute' => 'max_time',
    ];

    $attributes['start_time_all_groups'] = [
        // 'type' => DateTimePicker::TYPE_INPUT,
        'type' => Form::INPUT_WIDGET,
        'widgetClass' => 'kartik\datetime\DateTimePicker',
        'options' => [
            'removeButton' => false,
            'pickerButton' => ['icon' => 'time'],
            'readonly' => TRUE,
            'attribute' => 'start_time_all_groups',
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ];

    $attributes['start_all_groups'] = [
      'type' => Form::INPUT_CHECKBOX
    ];

    $form = ActiveForm::begin([
        'id' => 'event-names-form',
        'type' => ActiveForm::TYPE_VERTICAL,
        'formConfig' => ['labelSpan' => 20],
        'action' => $model->isNewRecord ? ['event-names/create', 'action' => $action] : ['event-names/change-settings', 'event_ID' => $model->event_ID, 'action' => $action]]);

    echo Form::widget([
        'model' => $model,
        'form' => $form,
        'columns' => 2,
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
    <div class="alert alert-danger" id="no_stations_warning">
          Let op, je hebt geen posten aangemaakt. Je kunt starten, maar er kan
          geen tijd bijgehouden worden en alle deelnemers starten automatisch
          als je de status op 'Gestart' zet.
    </div>
</div>
