<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-post-passage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'posten_passage_ID') ?>

    <?= $form->field($model, 'post_ID') ?>

    <?= $form->field($model, 'event_ID') ?>

    <?= $form->field($model, 'group_ID') ?>

    <?= $form->field($model, 'gepasseerd') ?>

    <?php // echo $form->field($model, 'binnenkomst') ?>

    <?php // echo $form->field($model, 'vertrek') ?>

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
