<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-nood-envelop-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['nood-envelop/create'] : ['nood-envelop/update', 'route_ID' => $model->route_ID]]);

    echo $form->field($model, 'nood_envelop_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'coordinaat')->textInput(['maxlength' => true]);
    echo $form->field($model, 'opmerkingen')->textInput(['maxlength' => true]);
    echo $form->field($model, 'score')->textInput();
    echo $form->field($model, 'route_ID')->hiddenInput(['value'=> $model->route_ID])->label(false);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
