<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Route */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-route-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['route/create'] : ['route/update', 'id' => $model->route_ID]]);

        echo $form->field($model, 'route_name')->textInput(['maxlength' => true]);
        echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
        echo $form->field($model, 'day_date')->hiddenInput(['value'=> $model->day_date])->label(false);
    ?>
    <div class="form-route">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
