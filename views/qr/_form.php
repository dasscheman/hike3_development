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
    <?= Html::tag('b', Html::encode($model->getLatitude()), ['class' => 'latitude-rd']) ?>,
    <?= Html::tag('b', Html::encode($model->getLongitude()), ['class' => 'longitude-rd']) ?>
    <br>
    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['qr/create', 'route_ID' => $model->route_ID] : ['qr/' .  Yii::$app->controller->action->id, 'qr_ID' => $model->qr_ID]]);

    echo $form->field($model, 'qr_name')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Een herkenbare naam, deze naam wordt ook op qr-kaart geprint'
            )
        ]);
    echo $form->field($model, 'message')->textArea([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Je kunt hier een bericht zetten die de deelnemers te zien'
                . ' krijgen als ze de code scannen. Veld mag ook leeg blijven.'
                . ' Gebruik https:// voor een link.'
            )
        ]);
    echo $form->field($model, 'score')->textInput([
            'placeholder' => Yii::t(
                'app',
                'Punten (hele getallen), dit kunnen ook strafpunten (negatieve getallen) zijn.'
            )
    ]);

    echo $form->field($model, 'latitude')->textInput(['value'=> $model->latitude, 'readonly' => true, 'class' => 'form-control latitude']);
    echo $form->field($model, 'longitude')->textInput(['value'=> $model->longitude, 'readonly' => true, 'class' => 'form-control longitude']);
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
