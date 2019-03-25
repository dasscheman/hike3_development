<?php
use yii\helpers\Html;
use russ666\widgets\Countdown;
use yii\web\View;

/* @var $this GroupsController */
/* @var $data Groups */

if (Yii::$app->controller->action->id == 'status' || $model->getTimeTrailItem()->one()->getNextItem() != NULL) {
    ?>
    <div class="well">

        <p>
            <h3>
                <?php echo Html::encode($model->timeTrailItem->time_trail_item_name); ?>
            </h3>

            <div class="well">
                <?php
                // The Regular Expression filter
                $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
                $text = $model->timeTrailItem->instruction;
                // Check if there is a url in the text
                if(preg_match($reg_exUrl, $text , $url)) {
                    // make the urls hyper links
                    echo preg_replace($reg_exUrl, "<a href=" . $url[0] . ">" . Yii::t('app', 'link') . "</a> ", $text);
                } else {
                    // if no urls in the text just return the text
                    echo Html::encode($model->timeTrailItem->instruction);
                }?>
            </div>
            <?php

            $end_date = strtotime($model->start_time) + (strtotime($model->timeTrailItem->max_time)  - strtotime('TODAY'));

            if ($model->getTimeTrailItem()->one()->getNextItem() == NULL) {
                echo Html::encode(Yii::t('app', 'The time trail is finished.'));
            } elseif ($end_date > time()) {
                ?>
                <h1 id="countdown-time-trail-<?php echo $model->time_trail_check_ID ?>"</h1>
                <h1>
                    <?php
                    echo Countdown::widget([
                        'id' => 'counter',
                        'datetime' => date('Y-m-d H:i:s O', $end_date),
                        'format' => '%H:%M:%S',
                        'events' => [
                            // 'update' => 'function(){console.log(jQuery("#test").countdown);}',
                            'finish' => 'function(){location.reload()}',
                        ],
                    ]);
                    ?>
                </h1> <?php
            } else {
                echo Html::encode(Yii::t('app', 'Je bent te laat. Maar je moet nog steeds de QR scannen voor intructies naar het volgende item.'));
            }
            ?>
        </p>
    </div> <?php
}

if ($model->getTimeTrailItem()->one()->getNextItem() != NULL) {
    $id = 'countdown-time-trail-' . $model->time_trail_check_ID;
    $setTime = 1000;
    if(isset(Yii::$app->params["alternate_time"][$model->event_ID]['factor']){
        $setTime = $setTime * Yii::$app->params["alternate_time"][$model->event_ID]['factor']);
    }

    $this->registerJs(
        'setInterval(function() { runTimer(' . $end_date . ', "' . $id . '"); }, ' . $setTime .');',
        View::POS_LOAD,
        'counter');
}
?>
