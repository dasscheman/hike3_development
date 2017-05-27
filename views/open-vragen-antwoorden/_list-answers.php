<?php
use prawee\widgets\ButtonAjax;
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\DeelnemersEvent;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <?php
    $group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
    ?>
    <h4>
        <?php echo Html::encode($model->openVragen->open_vragen_name); ?>
    </h4>
    <b>
    <?php echo Html::encode($model->openVragen->getAttributeLabel('vraag')); ?>:
    </b>
    <?php echo Html::encode($model->openVragen->vraag); ?></br>
    <b>
    <?php echo Html::encode($model->openVragen->getAttributeLabel('score')); ?>:
    </b>
    <?php echo Html::encode($model->openVragen->score); ?></br>

    <b>
    <?php echo Html::encode($model->getAttributeLabel('antwoord_spelers')); ?>:
    </b>
    <?php echo Html::encode($model->antwoord_spelers); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('checked')); ?>:
    </b>
    <?php echo GeneralFunctions::printGlyphiconCheck($model->checked); ?>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('correct')); ?>:
    </b>
    <?php echo GeneralFunctions::printGlyphiconCheck($model->correct);?>

</div>
