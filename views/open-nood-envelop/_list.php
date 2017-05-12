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
        <h3>
        <?php echo Html::encode($model->nood_envelop_name); ?>
        </h3>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
        </b>
        <?php echo Html::encode($model->score); ?></br>

        <?php
        if ($model->isHintOpenedByGroup()) { ?>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('coordinaat')); ?>:
            </b>
            <?php echo Html::encode($model->coordinaat); ?> </br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('opmerkingen')); ?>:
            </b>
            <?php echo Html::encode($model->opmerkingen); ?></br>
            <b>
            <?php echo Html::encode($model->getHintOpenedByGroup()->getAttributeLabel('create_user_ID')); ?>:
            </b>
            <?php echo Html::encode($model->getHintOpenedByGroup()->createUser->username); ?></br>
            <b>
            <?php echo Html::encode($model->getHintOpenedByGroup()->getAttributeLabel('create_time')); ?>:
            </b>
            <?php echo Html::encode($model->getHintOpenedByGroup()->create_time);
        }

        Pjax::end(); ?>
        </div>
    </div>
</div>
