<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-nood-envelop-form">
    </br>
    <b>
        <?php echo Html::encode($model->coordinatenLabel('coordinaten')); ?>:
    </b>
    <?= Html::tag('b', Html::encode($model->getLatitude()), ['class' => 'latitude-rd']) ?>,
    <?= Html::tag('b', Html::encode($model->getLongitude()), ['class' => 'longitude-rd']) ?>
    <br>
    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['nood-envelop/create', 'route_ID' => $model->route_ID] : ['nood-envelop/' .  Yii::$app->controller->action->id, 'nood_envelop_ID' => $model->nood_envelop_ID]]);

    echo $form->field($model, 'nood_envelop_name')->textInput([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Herkenbare naam, waardoor de spelers weten waar deze hint over gaat.'
            )
        ]);
    echo $form->field($model, 'show_coordinates')->checkbox();
    echo $form->field($model, 'opmerkingen')->textArea([
            'maxlength' => true,
            'placeholder' => Yii::t(
                'app',
                'Hint om de spelers te helpen. Alleen zichtbaar wanneer een groep'
                .'de hint heeft geopend. Gebruik https:// voor een link.'
            )
        ]);
    echo $form->field($model, 'score')->textInput([
            'placeholder' => Yii::t(
                'app',
                'Strafpunten, gebruik positieve hele getallen.'
            )
        ]);

    echo $form->field($model, 'latitude')->textInput(['value'=> $model->latitude, 'readonly' => true, 'class' => 'form-control latitude']);
    echo $form->field($model, 'longitude')->textInput(['value'=> $model->longitude, 'readonly' => true, 'class' => 'form-control longitude']);
    echo $form->field($model, 'route_ID')->hiddenInput(['value'=> $model->route_ID])->label(false);
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $model->event_ID])->label(false);
    ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

        if (!$model->isNewRecord) {
            echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'update']);
        } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
