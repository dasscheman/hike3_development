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

    $attributes['max_time'] = [
        'type' => Form::INPUT_TEXT,
        // 'value' => '01/04/2005 08:17',
        'attribute' => 'max_time',
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
</div>
