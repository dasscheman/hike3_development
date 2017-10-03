<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-time-trail-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? [
            'time-trail/create'
        ] : [
            'time-trail/update',
            'time_trail_ID' => $model->time_trail_ID]]);

    echo $form->field($model, 'time_trail_name')->textInput(['maxlength' => true]);

    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
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