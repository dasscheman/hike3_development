<?php
use yii\helpers\Html;
use app\components\GeneralFunctions;

/* @var $this GroupsController */
/* @var $data Groups */

?>
    <div class="view">
    <h4>
        <?php echo Html::encode($model->timeTrailItem->time_trail_item_name); ?>
    </h4>
    <?php
    // The Regular Expression filter
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    $text = $model->timeTrailItem->instruction;
    // Check if there is a url in the text
    if(preg_match($reg_exUrl, $text , $url)) {
        // make the urls hyper links
        echo preg_replace($reg_exUrl, "<a href=" . $url[0] . "  target='_blank'>" . Yii::t('app', 'link') . "</a> ", $text);
    } else {
        // if no urls in the text just return the text
        echo Html::encode($model->timeTrailItem->instruction);
    }
    $end_date = strtotime($model->start_time) + (strtotime($model->timeTrailItem->max_time)  - strtotime('TODAY'));

    if (!$model->getTimeTrailItem()->one()->getNextItem()) {
        echo Html::encode(Yii::t('app', 'The time trail is finished.')); ?></br><?php
    } elseif ($model->end_time == NULL) {
        ?> <br><br><i> <?php
        if ($end_date>time()) {
            echo Html::encode(Yii::t('app', 'Tijd loopt nog.'));
        } else {
            echo Html::encode(Yii::t('app', 'Je bent te laat. Maar je moet nog steeds de QR scannen voor intructies naar het volgende item.'));
        }
        ?> </i> <?php
    } else {
        ?><br><br><b> <?php
        echo Html::encode($model->getAttributeLabel('succeded')); ?>: </b>
        <?php echo GeneralFunctions::printGlyphiconCheck($model->succeded);
    }?>
</div>
