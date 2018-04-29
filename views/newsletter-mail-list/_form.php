<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Newsletter;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterMailList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="newsletter-mail-list-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php
    $items = ArrayHelper::map(Newsletter::find()->all(), 'id', 'subject');
    ?>
	<?= $form->field($model, 'newsletter_id')->dropDownList($items); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
