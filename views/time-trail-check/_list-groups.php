<?php
use app\components\GeneralFunctions;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;
use app\models\TimeTrailCheck;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
        <?php
            echo AlertBlock::widget([
                'type' => AlertBlock::TYPE_ALERT,
                'useSessionFlash' => true,
                'delay' => 4000,
            ]);
        ?>
        <p>
            <h3>
                <?php echo Html::encode($model->group_name); ?>
            </h3>
            <?php
            $timeTrailCheck = TimeTrailCheck::find()
                ->where('time_trail_item_ID =:time_trail_item_id AND group_ID =:group_id')
                ->params([':time_trail_item_id' => $time_trail_item_id, ':group_id' => $model->group_ID]);

            if($timeTrailCheck->exists()) {
                $timeTrailCheckData = $timeTrailCheck->one();
                echo Html::tag('br');
                echo Html::tag('b', Html::encode($timeTrailCheckData->getAttributeLabel('succeded')));
                echo GeneralFunctions::printGlyphiconCheck($timeTrailCheckData->succeded);
                echo Html::tag('br');
                echo Html::tag('b', Html::encode($timeTrailCheckData->getAttributeLabel('start_time')));
                echo Html::encode(Yii::$app->setupdatetime->displayFormat($timeTrailCheckData->start_time, 'datetime', false, true));
                if(Yii::$app->setupdatetime->displayRealTime($timeTrailCheckData->start_time, 'datetime')){
                    echo  Html::tag('br');
                    echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($timeTrailCheckData->start_time, 'datetime')), ['class'=>'btn-xs']);
                }

                echo Html::tag('br');
                echo Html::tag('b', Html::encode($timeTrailCheckData->getAttributeLabel('end_time')));
                echo Html::encode(Yii::$app->setupdatetime->displayFormat($timeTrailCheckData->end_time, 'datetime', false, true));
                if(Yii::$app->setupdatetime->displayRealTime($timeTrailCheckData->end_time, 'datetime')){
                    echo  Html::tag('br');
                    echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($timeTrailCheckData->end_time, 'datetime')), ['class'=>'btn-xs']);
                }
            } ?>
        </p>
        </div>
    </div>
</div>
