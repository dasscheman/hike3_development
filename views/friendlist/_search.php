<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblFriendListSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-friend-list-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'friend_list_ID') ?>

    <?= $form->field($model, 'user_ID') ?>

    <?= $form->field($model, 'friends_with_user_ID') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'create_time') ?>

    <?php // echo $form->field($model, 'create_user_ID') ?>

    <?php // echo $form->field($model, 'update_time') ?>

    <?php // echo $form->field($model, 'update_user_ID') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
