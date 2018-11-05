<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Posten */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-posten-form">
    </br>
    <b>
        <?php echo Html::encode($model->coordinatenLabel('coordinaten')); ?>:
    </b>
    <?php echo Html::encode($model->getLatitude()); ?>,
    <?php echo Html::encode($model->getLongitude()); ?></br></br>
    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['posten/create', 'date' => $model->date] : ['posten/' .  Yii::$app->controller->action->id, 'post_ID' => $model->post_ID]]);

    echo $form->field($model, 'post_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'score')->textInput();
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    echo $form->field($model, 'date')->hiddenInput(['value'=> $model->date])->label(false);
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
