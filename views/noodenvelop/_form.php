<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-nood-envelop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nood_envelop_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_ID')->textInput() ?>

    <?= $form->field($model, 'route_ID')->textInput() ?>

    <?= $form->field($model, 'nood_envelop_volgorde')->textInput() ?>

    <?= $form->field($model, 'coordinaat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'opmerkingen')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'create_user_ID')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'update_user_ID')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
