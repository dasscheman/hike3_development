<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-trail-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'time_trail_item_ID') ?>

    <?= $form->field($model, 'time_trail_ID') ?>

    <?= $form->field($model, 'time_trail_item_name') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'event_ID') ?>

    <?php // echo $form->field($model, 'trail_item_volgorde') ?>

    <?php // echo $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'max_time') ?>

    <?php // echo $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'create_user_ID') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'update_user_ID') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
