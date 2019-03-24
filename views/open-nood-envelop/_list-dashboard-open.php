<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;
use app\models\DeelnemersEvent;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <?php
    $nood_envelop = $model->getNoodEnvelop()->one();
    $group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
    echo Html::tag('h4', Html::encode($nood_envelop->nood_envelop_name));
    echo Html::tag('b', Html::encode($nood_envelop->getAttributeLabel('score')) . ': ');
    echo Html::encode($nood_envelop->score);
    echo Html::tag('br');
    if ($nood_envelop->show_coordinates) {
        echo Html::tag('b', Html::encode($nood_envelop->coordinatenLabel('coordinaten')) . ': ');
        echo Html::encode($nood_envelop->getLatitude() . ', ');
        echo Html::encode($nood_envelop->getLongitude());
        echo Html::tag('br');
    }

    echo Html::tag('b', Html::encode($nood_envelop->getAttributeLabel('opmerkingen')) . ': ');
    echo Html::encode($nood_envelop->opmerkingen);
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->getAttributeLabel('create_user_ID')) . ': ');
    echo Html::encode($model->createUser->voornaam . ' ' . $model->createUser->achternaam);
    echo Html::tag('br');
    echo Html::tag('b', Html::encode($model->getAttributeLabel('create_time')) . ': ');
    echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->create_time, 'datetime', false, true));
    if(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')){
        echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')), ['class'=>'btn-xs']);
        echo  Html::tag('br');
    }?>
</div>
