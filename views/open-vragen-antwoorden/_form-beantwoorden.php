<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\models\DeelnemersEvent;

/* @var $this yii\web\View */
/* @var $model app\models\OpenVragenAntwoorden */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-open-vragen-antwoorden-form-beantwoorden">
    <?php

    $form = ActiveForm::begin([
        'action' => $model->isNewRecord ?
        ['open-vragen-antwoorden/beantwoorden', 'open_vragen_ID' => $modelVraag->open_vragen_ID] :
        ['open-vragen-antwoorden/update', 'open_vragen_ID' => $modelVraag->open_vragen_ID]]);
    ?>
    <b>
    <?php echo Html::encode($modelVraag->vraag); ?></br>
    </b>
    <?php
    echo $form->field($model, 'antwoord_spelers')->textarea(['rows' => 6]);
    echo $form->field($model, 'event_ID')->hiddenInput(['value'=> $modelVraag->event_ID])->label(false);
    echo $form->field($model, 'open_vragen_ID')->hiddenInput(['value'=> $modelVraag->open_vragen_ID])->label(false);
    ?>
    <div class="form-group">
        <?php

        echo Html::submitButton(
            Yii::t('app', 'Save'),
            [
                'class' => 'btn btn-xs btn-primary',
                'value'=>'beantwoord-vraag',
                'name'=>'submit'
            ]
        );
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
