<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="view">
    <?php
        echo Html::tag('h4', Html::encode($model->qr->qr_name));
        echo Html::tag('b',Html::encode($model->qr->getAttributeLabel('score')) . ': ');
        echo Html::encode($model->qr->score);
        echo Html::tag('br');
        if($model->qr->message != null) {
            ?><div class="btn-info"> <?php
            echo Html::tag('b', Html::encode($model->getAttributeLabel('message')) . ': ');
            echo Html::encode($model->qr->message);
            echo Html::tag('br');
            ?></div> <?php
        }
        echo Html::tag('b', Html::encode($model->getAttributeLabel('create_user_ID')) . ': ');
        echo Html::encode($model->createUser->voornaam . ' ' . $model->createUser->achternaam);
        echo Html::tag('br');
        echo Html::tag('b',Html::encode($model->getAttributeLabel('create_time')) . ': ');
        echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->create_time, 'datetime', false, true));
        if(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')){
            echo  Html::tag('br');
            echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')), ['class'=>'btn-xs']);
        }?>
</div>
