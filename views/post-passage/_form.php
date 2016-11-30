<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-post-passage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'post_ID')->textInput() ?>

    <?= $form->field($model, 'event_ID')->textInput() ?>

    <?= $form->field($model, 'group_ID')->textInput() ?>

    <?= $form->field($model, 'gepasseerd')->textInput() ?>

    <?= $form->field($model, 'binnenkomst')->textInput() ?>

    <?= $form->field($model, 'vertrek')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <?= $form->field($model, 'create_user_ID')->textInput() ?>

    <?= $form->field($model, 'update_time')->textInput() ?>

    <?= $form->field($model, 'update_user_ID')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
