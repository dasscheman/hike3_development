<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-vragen-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['open-vragen/create'] : ['open-vragen/update', 'id' => $model->route_ID]]); ?>

    <?= $form->field($model, 'open_vragen_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'omschrijving')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vraag')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'goede_antwoord')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'score')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
     
        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
