<?php
use app\components\GeneralFunctions;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;
use prawee\widgets\ButtonAjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <p>
            <?php
                Pjax::begin([
                    'id' => 'post-passage-list-' . $model->posten_passage_ID,
                    'enablePushState' => false
                ]);
                echo AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => 4000,

                ]);
            ?>
            <h3>
                <?php echo Html::encode($model->post->post_name); ?>
            </h3>
            <?php
            if (Yii::$app->user->can('organisatie')) {
                echo ButtonAjax::widget([
                   'name' => Yii::t('app', 'Edit station checkin'),
                   'route' => ['/post-passage/update', 'posten_passage_ID' => $model->posten_passage_ID],
                   'modalId' => '#main-modal',
                   'modalContent'=>'#main-content-modal',
                   'options' => [
                       'class' => 'btn btn-xs btn-success',
                       'title' => Yii::t('app', 'Edit station checkin'),
                       'disabled' => !Yii::$app->user->can('organisatie'),
                   ]
               ]);
            }
            ?>
        </p>
        <?php
            echo Html::tag('b', Html::encode($model->getAttributeLabel('gepasseerd')));
            echo GeneralFunctions::printGlyphiconCheck($model->gepasseerd);
            echo Html::tag('br');
            if(!$model->post->isStartPost()) {
                echo Html::tag('b', Html::encode($model->getAttributeLabel('binnenkomst')) . ': ');
                echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->binnenkomst, 'datetime', false, true));
                if(Yii::$app->setupdatetime->displayRealTime($model->binnenkomst, 'datetime')){
                    echo  Html::tag('br');
                    echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->binnenkomst, 'datetime')), ['class'=>'btn-xs']);
                }
                echo  Html::tag('br');
            }

            if(!$model->post->isEndPost()) {
                echo Html::tag('b',  Html::encode($model->getAttributeLabel('vertrek')) . ': ');
                echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->vertrek, 'datetime', false, true));
                if(Yii::$app->setupdatetime->displayRealTime($model->vertrek, 'datetime')){
                    echo  Html::tag('br');
                    echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->vertrek, 'datetime')), ['class'=>'btn-xs']);
                }
            }
            Pjax::end(); ?>
        </div>
    </div>
</div>
