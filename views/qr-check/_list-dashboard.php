<?php
use yii\helpers\Html;
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
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            $text = $model->qr->message;
            // Check if there is a url in the text
            if(preg_match($reg_exUrl, $text , $url)) {
                // make the urls hyper links
                echo preg_replace($reg_exUrl, "<a href=" . $url[0] . " target='_blank'>" . Yii::t('app', 'link') . "</a> ", $text);
            } else {
                // if no urls in the text just return the text
                echo Html::encode($model->qr->message);
            }
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
