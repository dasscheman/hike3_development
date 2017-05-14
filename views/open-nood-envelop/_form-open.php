<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenNoodEnvelop */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-nood-envelop-form">
    <?php

    $form = ActiveForm::begin([
        'action' => $model->isNewRecord ?
        ['open-nood-envelop/open', 'nood_envelop_ID' => $modelEnvelop->nood_envelop_ID] :
        ['open-nood-envelop/update', 'nood_envelop_ID' => $modelEnvelop->nood_envelop_ID]]); ?>

    <?php echo Html::encode($modelEnvelop->getAttributeLabel('nood_envelop_name')); ?>:
    </b>
    <?php echo Html::encode($modelEnvelop->nood_envelop_name); ?> </br>
    <b>
    <?php
    if ($model->isNewRecord) {
        echo Html::encode(Yii::t('app', 'Are you sure you want to open this hint?'));
        echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $modelEnvelop->event_ID])->label(false);
        echo $form->field($model, 'nood_envelop_ID')->hiddenInput(['value'=> $modelEnvelop->nood_envelop_ID])->label(false);
    } else {
        echo $form->field($model, 'group_ID')->textInput();
        echo $form->field($model, 'opened')->textInput();
        echo $form->field($model, 'create_time')->textInput();
        echo $form->field($model, 'create_user_ID')->textInput();
        echo $form->field($model, 'update_time')->textInput();
        echo $form->field($model, 'update_user_ID')->textInput();
    }?>

    <div class="form-group">
        <?php

        echo Html::submitButton(
            Yii::t('app', 'Open hint'),
            [
                'class' => 'btn btn-xs btn-primary',
                'value'=>'open-hint',
                'name'=>'submit'
            ]
        );
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
