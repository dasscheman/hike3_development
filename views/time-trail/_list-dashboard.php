<?php
use yii\helpers\Html;
use russ666\widgets\Countdown;
use app\components\GeneralFunctions;

/* @var $this GroupsController */
/* @var $data Groups */

?>
    <div class="view">
    <h4>
        <?php echo Html::encode($model->timeTrailItem->time_trail_item_name); ?>
    </h4>
    
    <?php echo Html::encode($model->timeTrailItem->instruction); ?></br>
    <?php
    $end_date = strtotime($model->start_time) + (strtotime($model->timeTrailItem->max_time)  - strtotime('TODAY'));

    if (!$model->getTimeTrailItem()->one()->getNextItem()) {
        echo Html::encode(Yii::t('app', 'The time trail is finished.')); ?></br><?php
    } elseif ($model->end_time == NULL) {
        if ($end_date>time()) {
        ?>
        <h1>
            <?php
            echo Countdown::widget([
                'datetime' => date('Y-m-d H:i:s O', $end_date),
                'format' => '%H:%M:%S',
                'events' => [
                    'finish' => 'function(){location.reload()}',
                ],
            ]);
            ?>
        </h1> <?php
        } else {
            echo Html::encode(Yii::t('app', 'You are to late! But you still have to scan the next item to get instructions for the next item.'));
        }
    } else {
        ?> <b> <?php
        echo Html::encode($model->getAttributeLabel('succeded')); ?>: </b>
        <?php echo GeneralFunctions::printGlyphiconCheck($model->succeded);
    }?>
</div>

