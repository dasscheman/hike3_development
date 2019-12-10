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
$deelnemersEvent = new DeelnemersEvent();
?>
    <div class="view">
    <?php
    echo Html::tag('h4', Html::encode($model->nood_envelop_name));
    if (!$model->isHintOpenedByGroup()) {
        $group_ID = $deelnemersEvent->getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);
        if ( !Yii::$app->user->can('deelnemerIntroductie') && !Yii::$app->user->can('deelnemerGestartTime')) {
            // disable functie van html::a lijkt niet te werken. Daarom dummy burtton:
            echo Html::Button(
                'Open ',
                [
                    'title' => Yii::t('app', 'Kan hint nu niet openen'),
                    'class'=>'btn btn-xs btn-success',
                    'disabled' => true,
                ]
            );
        } else {
            echo Html::a(
              'Open',
              [
                '/open-nood-envelop/open',
                'nood_envelop_ID'=>$model->nood_envelop_ID,
                'group_ID' => $group_ID
              ],
              [
                'class' => 'btn btn-xs btn-success',
                'data' => [
                    'confirm' => Yii::t('app', 'Weet je zeker dat je deze hint wilt openen?'),
                ],
              ]
            );
        }
        echo Html::tag('br');
    }
    echo Html::tag('b', Html::encode($model->getAttributeLabel('score')) . ': ');
    echo Html::encode($model->score);
    echo Html::tag('br');
    if ($model->isHintOpenedByGroup()) {
        if ($model->show_coordinates) {
            echo Html::tag('b', Html::encode($model->coordinatenLabel('coordinaten')) . ': ');
            echo Html::encode($model->getLatitude() . ', ');
            echo Html::encode($model->getLongitude());
            echo Html::tag('br');
        }
        echo Html::tag('b', Html::encode($model->getAttributeLabel('opmerkingen')) . ': ');
        echo Html::encode($model->opmerkingen);
        echo Html::tag('br');
        echo Html::tag('b', Html::encode($model->getOpenNoodEnvelops()->one()->getAttributeLabel('create_user_ID')) . ': ');
        echo Html::encode($model->getOpenNoodEnvelops()->one()->createUser->voornaam . ' ' . $model->getOpenNoodEnvelops()->one()->createUser->achternaam);
        echo Html::tag('br');
        echo Html::tag('b', Html::encode($model->getOpenNoodEnvelops()->one()->getAttributeLabel('create_time')) . ': ');
        echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->getOpenNoodEnvelops()->one()->create_time, 'datetime', false, true));
        if(Yii::$app->setupdatetime->displayRealTime($model->getOpenNoodEnvelops()->one()->create_time, 'datetime')){
            echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getOpenNoodEnvelops()->one()->create_time, 'datetime')), ['class'=>'btn-xs']);
            echo  Html::tag('br');
        }
    }?>
    </div>
