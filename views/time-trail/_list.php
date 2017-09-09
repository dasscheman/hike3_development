<?php
use yii\helpers\Html;
use russ666\widgets\Countdown;

/* @var $this GroupsController */
/* @var $data Groups */

if (Yii::$app->controller->action->id == 'status' && $model->getTimeTrailItem()->one()->getNextItem() == NULL) {
    ?>
    <div class="well">

        <p>
            <h3>
                <?php echo Html::encode($model->timeTrailItem->time_trail_item_name); ?>
            </h3>

            <div class="well">
                <?php echo Html::encode($model->timeTrailItem->instruction); ?>
            </div>
            <?php
            $end_date = strtotime($model->start_time) + (strtotime($model->timeTrailItem->max_time)  - strtotime('TODAY'));

            if ($model->getTimeTrailItem()->one()->getNextItem() == NULL) {
                echo Html::encode(Yii::t('app', 'The time trail is finished.'));
            } elseif ($end_date>time()) {
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
            ?>
        </p>
    </div> <?php
}
