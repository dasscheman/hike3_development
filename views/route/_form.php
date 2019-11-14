<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Route */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-route-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? [
	    'route/create'
	] : [
 	    'route/update',
	    'route_ID' => $model->route_ID]]);

        echo $form->field($model, 'route_name')->textInput(['maxlength' => true]);
        echo $form->field($model, 'start_datetime')->widget(DateTimePicker::classname(), [
          	'options' => [
                'placeholder' => 'Starttijd van route onderdeel',
                'value' => Yii::$app->setupdatetime->displayFormat($model->start_datetime, 'datetime_no_sec', false, false),
            ],
          	'pluginOptions' => [
          		  'autoclose' => true
          	]
        ]);
        // dateTime();
        echo $form->field($model, 'end_datetime')->widget(DateTimePicker::classname(), [
          	'options' => [
                'placeholder' => 'Eindtijd van route onderdeel',
                'value' => Yii::$app->setupdatetime->displayFormat($model->end_datetime, 'datetime_no_sec', false, false),
            ],
          	'pluginOptions' => [
          		  'autoclose' => true
          	]
        ]);
        // ->dateTime();
        echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    ?>
    <div class="form-route">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
