<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Posten */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-posten-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['posten/create'] : ['posten/update', 'id' => $model->route_ID]]);

    echo $form->field($model, 'post_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'score')->textInput();
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    echo $form->field($model, 'date')->hiddenInput(['value'=> $model->date])->label(false);
    ?>
    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
