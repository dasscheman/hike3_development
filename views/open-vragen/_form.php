<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-vragen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'open_vragen_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'event_ID')->textInput() ?>

    <?= $form->field($model, 'route_ID')->textInput() ?>

    <?= $form->field($model, 'vraag_volgorde')->textInput() ?>

    <?= $form->field($model, 'omschrijving')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vraag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goede_antwoord')->textInput(['maxlength' => true]) ?>

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
