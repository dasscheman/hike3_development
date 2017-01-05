<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\FriendList;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-groups-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['groups/create'] : ['groups/update', 'id' => $model->group_ID]]); ?>

    <?= $form->field($model, 'group_name')->textInput(['maxlength' => true]);

//        d(FriendList::getFriendsForEvent());
//        dd($model->users_temp);
    echo $form->field($model, 'users_temp')->widget(Select2::classname(), [
        'value' => $model->users_temp,
        'id' => $model->group_ID,
        'data' => FriendList::getFriendsForEvent($model->group_ID),
        'options' => [
            'placeholder' => 'Filter as you type ...',
            'id' => $model->group_ID,
            'class' => "form-control",
            'multiple' => TRUE,
//        'initValueText' => $model->users_temp,
//        'value' => $model->users_temp,
        ],
        'pluginOptions' => [
              'tags' => true,
//        'initSelection' => function (){
//        return $model->users_temp;},
//        'value' => $model->users_temp,
//            S'allowClear' => true
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'value'=>'groups/create', 'name'=>'submit']) ?>
        <?php
        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>