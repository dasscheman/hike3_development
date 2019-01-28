<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <?php
        if ($model->isSilentStationCkeckedByGroup()) {
            echo Html::tag('h3', Html::encode($model->qr_name));
            echo Html::tag('b', Html::encode($model->getAttributeLabel('score')));
            echo Html::encode($model->score);
            echo Html::tag('br');
            echo Html::tag('b', Html::encode($model->getSilentStationCkeckedByGroup()->getAttributeLabel('create_user_ID')) . ':');
            echo Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_user_ID, 'datetime', false, true));

            if(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_user_ID, 'datetime')){
                echo  Html::tag('br');
                echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_user_ID, 'datetime')), ['class'=>'btn-xs']);
            }
            echo Html::tag('br');
            echo Html::tag('b', Html::encode($model->getSilentStationCkeckedByGroup()->getAttributeLabel('create_time')) . ':');
            echo Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_time, 'datetime', false, true));
            if(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_time, 'datetime')){
                echo  Html::tag('br');
                echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getSilentStationCkeckedByGroup()->create_time, 'datetime')), ['class'=>'btn-xs']);
            }
        }?>

        </div>
    </div>
</div>
