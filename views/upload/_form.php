<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UploadForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-route-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'importFile')->fileInput() ?>

        <button>Submit</button>

    <?php ActiveForm::end() ?>
</div>
