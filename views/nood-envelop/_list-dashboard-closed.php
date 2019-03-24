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
    echo Html::tag('h4', Html::encode($model->nood_envelop_name));
    if (!$model->isHintOpenedByGroup()) {
        $group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);

        echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Open Hint'),
            'route' => [
                '/open-nood-envelop/open',
                'nood_envelop_ID'=>$model->nood_envelop_ID,
                'group_ID' => $group_ID
            ],
            'modalId'=>'#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-xs btn-success',
                'title' => Yii::t('app', 'Open Hint'),
                'disabled' => !Yii::$app->user->can('deelnemerIntroductie') && !Yii::$app->user->can('deelnemerGestartTime')
            ]
        ]);
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
