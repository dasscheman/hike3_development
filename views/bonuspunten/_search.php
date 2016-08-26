<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspuntenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-bonuspunten-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bouspunten_ID') ?>

    <?= $form->field($model, 'event_ID') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'post_ID') ?>

    <?= $form->field($model, 'group_ID') ?>

    <?php // echo $form->field($model, 'omschrijving') ?>

    <?php // echo $form->field($model, 'score') ?>

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
