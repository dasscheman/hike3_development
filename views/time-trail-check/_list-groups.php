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
                ?>
                </br>
                <b>
                <?php echo Html::encode($timeTrailCheckData->getAttributeLabel('succeded')); ?>
                </b>
                <?php echo GeneralFunctions::printGlyphiconCheck($timeTrailCheckData->succeded); ?></br>
                <b>
                <?php echo Html::encode($timeTrailCheckData->getAttributeLabel('start_time')); ?>
                </b>
                <?php echo Html::encode($timeTrailCheckData->start_time); ?></br>
                <b>
                <?php echo Html::encode($timeTrailCheckData->getAttributeLabel('end_time')); ?>
                </b>
                <?php echo Html::encode($timeTrailCheckData->end_time); ?></br>
            <?php } ?>
        </p>
        </div>
    </div>
</div>
