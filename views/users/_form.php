<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker; 

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'voornaam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'achternaam')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'organisatie')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'birthdate')->widget(DatePicker::classname(),
        [
            'name' => 'birthdate',
            'value'=> $model->birthdate,
            //'type' => DatePicker::TYPE_INLINE,
            'convertFormat' => true,
            'pluginOptions' => [
               'format' => 'yyyy-MM-dd',
                    'todayHighlight' => true
            ]
        ]); 
    ?> 

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
