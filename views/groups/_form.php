<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-groups-form">

    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['groups/create'] : ['groups/update', 'group_ID' => $model->group_ID]]); ?>

    <?php
    echo $form->field($model, 'group_name')->textInput(['maxlength' => true]);

    echo $form->field($model, 'users_temp')->widget(Select2::classname(), [
        'value' => $model->users_temp,
        'id' => $model->group_ID,
        'data' => $friendList->getFriendsForEvent($model->group_ID),
        'options' => [
            'placeholder' => 'Filter as you type ...',
            'id' => $model->group_ID,
            'class' => "form-control",
            'multiple' => true,
        ],
        'pluginOptions' => [
              'tags' => true,
        ]
    ]);

    echo $form->field($model, 'users_email_temp')->textarea([
            'rows' => 6,
            'placeholder' => Yii::t(
                'app',
                'Use users emails to add multiple players at once to this group. '
                . 'Email must be known in the hike-app. In case you not jet friends, a friendship request is send aswell. '
                . 'Forexample: tets@test.nl, user@test.nl, player@test.nl'
            )
        ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
            [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                'id'=> $model->isNewRecord ?
                    'groups-form-create' :
                    'groups-update-' . $model->group_ID,
                'value'=> $model->isNewRecord ? 'create' : 'update',
                'name'=>'action'
            ]
    ) ?>

        <?php
        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'action']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
