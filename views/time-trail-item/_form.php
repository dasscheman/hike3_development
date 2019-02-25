<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\widgets\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="tbl-time-trail-item-form">
    </br>
    <b>
        <?php echo Html::encode($model->coordinatenLabel('coordinaten')); ?>:
    </b>
    <?= Html::tag('b', Html::encode($model->getLatitude()), ['class' => 'latitude-rd']) ?>,
    <?= Html::tag('b', Html::encode($model->getLongitude()), ['class' => 'longitude-rd']) ?>
    <br>

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? [
            'time-trail-item/create',
            'time_trail_ID' => $model->time_trail_ID
        ] : [
            'time-trail-item/' .  Yii::$app->controller->action->id,
            'time_trail_item_ID' => $model->time_trail_item_ID
        ]]);

    echo $form->field($model, 'time_trail_item_name')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t(
            'app',
            'Recognizable name which will be printed on the qr sheet.'
        )
    ]);
    echo $form->field($model, 'score')->textInput([
        'maxlength' => true,
        'placeholder' => Yii::t(
            'app',
            'Points a group get for scannin the next qr code in time.'
        )
    ]);
    echo $form->field($model, 'instruction')->textarea([
        'rows' => 6,
        'placeholder' => Yii::t(
            'app',
            'Instructions for the next point. They see this instruction when they scan this item.'
        )
    ]);

     echo $form->field($model, 'max_time')->widget(
        TimePicker::classname(),
        [
            'attribute' => 'max_time',
            'pluginOptions' => [
                'id' => $model->time_trail_ID,
                'showSeconds' => false,
                'showMeridian' => false,
                'minuteStep' => 1,
                'defaultTime' => '10:00'
            ],
            'options' => ['id' => $model->time_trail_ID]
        ]
    )->label(
        Yii::t(
            'app',
            'Max tijd (hh:mm) een groep krijgt om het volgende item te scannen.'
        )
    );

    echo Html::tag('i', Html::encode(  Yii::t(
          'app',
          'Let op! bij het laatste tijdrit-item hoef je geen tijd in te vullen.'
    )));
    echo Html::tag('br');
    echo Html::tag('br');
    echo $form->field($model, 'latitude')->textInput(['value'=> $model->latitude, 'readonly' => true, 'class' => 'form-control latitude']);
    echo $form->field($model, 'longitude')->textInput(['value'=> $model->longitude, 'readonly' => true, 'class' => 'form-control longitude']);
    echo $form->field($model, 'time_trail_ID')->hiddenInput(['value'=> $model->time_trail_ID])->label(false);
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    ?>

    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
