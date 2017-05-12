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
    Pjax::begin(['id' => 'open-nood-envelop-form-' . $model->nood_envelop_ID, 'enablePushState' => false]);
    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]);
    $form = ActiveForm::begin([]); ?>

    <?php echo Html::encode($modelEnvelop->getAttributeLabel('nood_envelop_name')); ?>:
    </b>
    <?php echo Html::encode($modelEnvelop->nood_envelop_name); ?> </br>
    <b>
    <?php
    if ($model->isNewRecord) {
        echo Html::encode(Yii::t('app', 'Are you sure you want to open this hint?'));
    } else { ?>

        <?= $form->field($model, 'group_ID')->textInput() ?>

        <?= $form->field($model, 'opened')->textInput() ?>

        <?= $form->field($model, 'create_time')->textInput() ?>

        <?= $form->field($model, 'create_user_ID')->textInput() ?>

        <?= $form->field($model, 'update_time')->textInput() ?>

        <?= $form->field($model, 'update_user_ID')->textInput();
    }?>

    <div class="form-group">
        <?php

        echo Html::a(
            Yii::t('app', 'Open hint'),
            ['/open-nood-envelop/open', 'nood_envelop_ID' => $modelEnvelop->nood_envelop_ID],
            ['class' => 'btn btn-xs btn-primary'],
            ['data-pjax' => 'open-nood-envelop-list-' . $model->nood_envelop_ID]
        );

        echo Html::a(
            Yii::t('app', 'Cancel'),
            ['/open-nood-envelop/cancel-opening', 'id'=>$modelEnvelop->nood_envelop_ID],
            ['class' => 'btn btn-xs btn-danger'],
            ['data-pjax' => 'open-nood-envelop-list-' . $model->nood_envelop_ID]
        ); ?>
    </div>
    <?php ActiveForm::end();
    Pjax::end(); ?>
</div>
