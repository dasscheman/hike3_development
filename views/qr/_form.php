<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Qr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-qr-form">
    </br>
    <b>
        <?php echo Html::encode($model->coordinatenLabel('coordinaten')); ?>:
    </b>
    <?php echo Html::encode($model->getLatitude()); ?>, 
    <?php echo Html::encode($model->getLongitude()); ?></br></br>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['qr/create', 'route_ID' => $model->route_ID] : ['qr/' .  Yii::$app->controller->action->id, 'qr_ID' => $model->qr_ID]]);

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
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
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
