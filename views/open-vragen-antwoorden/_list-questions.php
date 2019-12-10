<?php
use prawee\widgets\ButtonAjax;
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\DeelnemersEvent;
use app\models\OpenVragenAntwoorden;
use yii\widgets\ActiveForm;

/* @var $this GroupsController */
/* @var $data Groups */

$deelnemersEvent = new DeelnemersEvent();
$antwoordModel = new OpenVragenAntwoorden([
    'event_ID' => $model->event_ID,
    'open_vragen_ID' => $model->open_vragen_ID]);

?>
<div class="view">
    <?php
    $group_ID = $deelnemersEvent->getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
    echo Html::tag('h4',
        Html::encode($model->open_vragen_name) .
        // Html::tag('b', $model->score)
        Html::tag('b', ' (' . $model->score . ')')
    );
    echo Html::encode($model->omschrijving);
    Html::tag('br'); ?>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('vraag')); ?>:
    </b>
    <?php echo Html::encode($model->vraag); ?></br>
    <b>
    <?php
    $form = ActiveForm::begin([
        'id' => 'event-names-form',
        'action' => [
            'open-vragen-antwoorden/beantwoorden',
            'open_vragen_ID' => $antwoordModel->open_vragen_ID
        ]
    ]);

    echo $form->field($antwoordModel, 'antwoord_spelers')->textarea(['rows' => 6]);
    echo $form->field($antwoordModel, 'event_ID')->hiddenInput(['value'=> $antwoordModel->event_ID])->label(false);
    echo $form->field($antwoordModel, 'open_vragen_ID')->hiddenInput(['value'=> $antwoordModel->open_vragen_ID])->label(false);

    ?>
    <div class="form-group">
        <?php
        echo Html::submitButton(
            Yii::t('app', 'beantwoorden'),
            [
                'class' => 'btn btn-xs btn-success',
                'value' => 'beantwoord-vraag',
                'name' => 'beantwoord'
            ]
        );
    ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
