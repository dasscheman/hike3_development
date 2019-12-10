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
    echo Html::tag('h4', Html::encode($model->openVragen->open_vragen_name));
    echo Html::tag('b', Html::encode($model->openVragen->getAttributeLabel('vraag')) . ': ');
    echo Html::encode($model->openVragen->vraag);
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->openVragen->getAttributeLabel('score')) . ': ');
    echo Html::encode($model->openVragen->score);
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->getAttributeLabel('antwoord_spelers')) . ': ');
    echo Html::encode($model->antwoord_spelers);
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->getAttributeLabel('checked')) . ': ');
    echo GeneralFunctions::printGlyphiconCheck($model->checked);
    if ($model->checked) {
        echo Html::tag('br');
        echo Html::tag('b', Html::encode($model->getAttributeLabel('correct')) . ': ');
        echo GeneralFunctions::printGlyphiconCheck($model->correct);
    }
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->getAttributeLabel('update_time')) . ': ');
    echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->create_time, 'datetime', false, true));
    if (Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')){
        echo  Html::tag('br');
        echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')), ['class'=>'btn-xs']);
    }
    ?>
</div>
