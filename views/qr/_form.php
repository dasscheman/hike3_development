<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Qr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-qr-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['qr/create', 'route_ID' => $model->route_ID] : ['qr/update', 'qr_ID' => $model->qr_ID]]);

    echo $form->field($model, 'qr_name')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Recognizable name for this silent station, visable by players when they scanned the qr'
            )
        ]);
    echo $form->field($model, 'score')->textInput([
            'placeholder' => Yii::t(
                'app',
                'Points for scanning. You can use positive and negative (penalty point) integers.'
            )
    ]);
    echo $form->field($model, 'route_ID')->hiddenInput(['value'=> $model->route_ID])->label(false);
    ?>
    <div class="form-qr">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
