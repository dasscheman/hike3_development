<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">

        <p>
        <?php
        Pjax::begin(['id' => 'open-nood-envelop-list-' . $model->nood_envelop_ID, 'enablePushState' => false]);
        echo AlertBlock::widget([
            'type' => AlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => 4000,

        ]);
        if (!$model->isHintOpenedByGroup()) {
            echo Html::a(
                Yii::t('app', 'Open Hint'),
                ['/open-nood-envelop/create', 'nood_envelop_ID'=>$model->nood_envelop_ID],
                ['class' => 'btn btn-xs btn-success'],
                ['data-pjax' => 'open-nood-envelop-list-' . $model->nood_envelop_ID]
            );
        }

        ?>
        </p>
        <?php
            echo Html::tag('h3', Html::encode($model->nood_envelop_name));
            echo Html::tag('b', Html::encode($model->getAttributeLabel('score')) . ': ');
            echo Html::encode($model->score);
            echo Html::tag('br');
            if ($model->isHintOpenedByGroup()) {
                echo Html::tag('b', Html::encode($model->getAttributeLabel('coordinaat')) . ': ');
                echo Html::encode($model->coordinaat);
                echo Html::tag('br');
                echo Html::tag('b', Html::encode($model->getAttributeLabel('opmerkingen')) . ': ');
                echo Html::encode($model->opmerkingen);
                echo Html::tag('br');
                echo Html::tag('b', Html::encode($model->getHintOpenedByGroup()->getAttributeLabel('create_user_ID')) . ': ');
                echo Html::encode($model->getHintOpenedByGroup()->createUser->voornaam . ' ' . $model->getHintOpenedByGroup()->createUser->achternaam);
                echo Html::tag('br');
                echo Html::tag('b', Html::encode($model->getHintOpenedByGroup()->getAttributeLabel('create_time')) . ': ');
                echo Html::encode(
                    Yii::$app->setupdatetime->displayFormat(
                        $model->getHintOpenedByGroup()->create_time,
                        'datetime',
                        false,
                        true
                    )
                );
                if(Yii::$app->setupdatetime->displayRealTime($model->getHintOpenedByGroup()->create_time, 'datetime')){
                    echo  Html::tag('br');
                    echo  Html::tag('i', Html::encode(Yii::$app->setupdatetime->displayRealTime($model->getHintOpenedByGroup()->create_time, 'datetime')), ['class'=>'btn-xs']);
                }
            }
            Pjax::end(); ?>
        </div>
    </div>
</div>
