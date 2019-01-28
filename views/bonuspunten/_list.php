<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <p>
                <?php
                    Pjax::begin([
                        'id' => 'bonuspunten-list-' . $model->bouspunten_ID,
                        'enablePushState' => false
                    ]);
                    echo AlertBlock::widget([
                        'type' => AlertBlock::TYPE_ALERT,
                        'useSessionFlash' => true,
                        'delay' => 4000,

                    ]);
                ?>
                <h3>
                    <?php echo Html::encode($model->omschrijving); ?>
                </h3>
                <?php
                if (Yii::$app->user->can('organisatie')) {
                    echo ButtonAjax::widget([
                       'name' => Yii::t('app', 'Bewerken bonuspunten'),
                       'route' => ['/bonuspunten/update', 'bonuspunten_ID' => $model->bouspunten_ID],
                       'modalId' => '#main-modal',
                       'modalContent'=>'#main-content-modal',
                       'options' => [
                           'class' => 'btn btn-xs btn-success',
                           'title' => Yii::t('app', 'Bewerken bonuspunten'),
                           'disabled' => !Yii::$app->user->can('organisatie'),
                       ]
                   ]);
                }
                ?>
            </p>
            <?php
            echo Html::tag('b', Html::encode($model->getAttributeLabel('score')) . ': ');
            echo Html::encode($model->score);
            echo Html::tag('br');
            echo Html::tag('b', Html::encode($model->getAttributeLabel('create_user_ID')) . ': ');
            echo Html::encode($model->createUser->voornaam . ' ' . $model->createUser->achternaam);
            echo Html::tag('br');
            echo Html::tag('b', Html::encode($model->getAttributeLabel('create_time')) . ': ');
            echo Html::encode(Yii::$app->setupdatetime->displayFormat($model->create_time, 'datetime', false, true));
            if(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')){
                echo  Html::tag('br');
                echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->create_time, 'datetime')), ['class'=>'btn-xs']);
            }
            Pjax::end(); ?>
        </div>
    </div>
</div>
