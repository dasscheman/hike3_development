<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-nood-envelop-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['nood-envelop/create'] : ['nood-envelop/update', 'id' => $model->route_ID]]); ?>

    <?= $form->field($model, 'nood_envelop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coordinaat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'opmerkingen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
