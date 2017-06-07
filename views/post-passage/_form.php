<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\DateTimePicker;
use app\models\Groups;

/* @var $this yii\web\View */
/* @var $model app\models\PostPassage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-post-passage-form">

    <?php
    $form = ActiveForm::begin([
        'action' => $action === 'update' ?
            ['post-passage/update', 'posten_passage_ID' => $model->posten_passage_ID] :
            [
                'post-passage/check-station',
                'post_ID' => $model->post_ID,
                'group_ID' => $model->group_ID,
                'action' => $action
            ]
        ]);
    if($action === 'update') {
        echo $form->field($model, 'gepasseerd')->checkBox(['uncheck' => 0, 'selected' => TRUE]);
    }
    if ($action === 'checkin' ||
        $action === 'update') {
        echo $form->field($model, 'binnenkomst')->widget(
            DateTimePicker::classname(), [
            	'options' => ['placeholder' => Yii::t('app', 'Enter incheck time')],
                'type' => DateTimePicker::TYPE_INPUT,
            	'pluginOptions' => [
            		'autoclose' => true
            	]
        ]);
    }
    if ($action === 'start' ||
        $action === 'checkout' ||
        $action === 'update') {
        echo  $form->field($model, 'vertrek')->widget(
            DateTimePicker::classname(), [
            	'options' => ['placeholder' => Yii::t('app', 'Enter leave time')],
                'type' => DateTimePicker::TYPE_INPUT,
            	'pluginOptions' => [
            		'autoclose' => true
            	]
        ]);
    }
    ?>

    <div class="form-post-passage">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
