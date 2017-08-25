<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="tbl-time-trail-item-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? [
            'time-trail-item/create',
            'time_trail_ID' => $model->time_trail_ID
        ] : [
            'time-trail-item/update',
            'time_trail_item_ID' => $model->time_trail_item_ID
        ]]);

    echo $form->field($model, 'time_trail_item_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'score')->textInput(['maxlength' => true]);
    echo $form->field($model, 'time_trail_ID')->hiddenInput(['value'=> $model->time_trail_ID])->label(false);
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    echo $form->field($model, 'max_time')->widget(
        TimePicker::classname(),
        [
            'attribute' => 'max_time',
            'pluginOptions' => [
                'showSeconds' => FALSE,
                'showMeridian' => FALSE,
                'minuteStep' => 1,
                'defaultTime' => '10:00'
            ]
        ]
    );

    ?>
    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>