<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Routebook */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="routebook-form">
    <?php
    $form = ActiveForm::begin();
    echo $form->field($model, 'tekst')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'full',
            'clientOptions' => [
                'filebrowserUploadUrl' => Url::to([
                    'routebook/upload',
                    'routebook_ID' => $model->routebook_ID,
                    'event_ID' => $model->event_ID,
                    'route_ID' => $model->route_ID
                ])
            ]
        ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
