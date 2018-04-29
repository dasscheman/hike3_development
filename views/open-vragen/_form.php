<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OpenVragen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-vragen-form">

    <?php

    $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['open-vragen/create', 'route_ID' => $model->route_ID] : ['open-vragen/' .  Yii::$app->controller->action->id, 'open_vragen_ID' => $model->open_vragen_ID]]);
    echo $form->field($model, 'open_vragen_name')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Recognizable name for this question, visable by players.'
            )
        ]);
    echo $form->field($model, 'omschrijving')->textarea([
            'rows' => 6,
            'placeholder' => Yii::t(
                'app',
                'Optional extra information, visable by players.'
            )
        ]);
    echo $form->field($model, 'vraag')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'The actual question, visable by players.'
            )
        ]);
    echo $form->field($model, 'goede_antwoord')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'The correct answer. This field is NEVER visable by players'
            )
        ]);
    echo $form->field($model, 'score')->textInput(['placeholder' => Yii::t(
            'app',
            'Points for passing this station. Use positive integers.'
        )]);
    echo $form->field($model, 'latitude')->textInput(['readonly' => true, 'class' => 'form-control latitude']);
    echo $form->field($model, 'longitude')->textInput(['readonly' => true, 'class' => 'form-control longitude']);
    echo $form->field($model, 'route_ID')->hiddenInput(['value'=> $model->route_ID])->label(false);
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    ?>

    <div class="form-open-vragen">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
