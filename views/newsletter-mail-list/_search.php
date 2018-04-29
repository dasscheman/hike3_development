<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\vendor\yiimodules\newsletter\models\CampaignsQueueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaigns-queue-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'newsletter_campaigns_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'send_time') ?>

    <?php // echo $form->field($model, 'is_sent') ?>

    <?php // echo $form->field($model, 'created_by_user_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
