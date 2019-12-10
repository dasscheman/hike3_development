<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use app\models\NoodEnvelop;

/* @var $this GroupsController */
/* @var $data Groups */
$noodEnvelop = new NoodEnvelop();
?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <h3 class="max-titel-route-items-height">
                <b>
                  <?php echo Html::encode($model->nood_envelop_name); ?>
                </b>
            </h3>
            <p>
                <?php
                 echo ButtonAjax::widget([
                    'name' => Yii::t('app', 'Modify hint'),
                    'route'=>['/nood-envelop/update', 'nood_envelop_ID' => $model->nood_envelop_ID],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-xs btn-success',
                        'title'=> Yii::t('app', 'Modify hint'),
                        'disabled' => !Yii::$app->user->can('organisatie'),
                    ]
                ]);
                ?> </br> <?php

                echo Html::Button(
                    '<span class="glyphicon glyphicon-chevron-left"></span>',
                    [
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                            '/nood-envelop/move-up-down',
                            'nood_envelop_id' => $model->nood_envelop_ID,
                            'up_down' => 'up']) . "';",
                        'title' => Yii::t('app', 'Move up'),

                        'class'=>'btn btn-primary btn-xs',
                        'disabled' => !$noodEnvelop->lowererOrderNumberExists($model->nood_envelop_ID),
                    ]
                );

                echo Html::Button(
                    '<span class="glyphicon glyphicon-chevron-right"></span>',
                    [
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                            '/nood-envelop/move-up-down',
                            'nood_envelop_id' => $model->nood_envelop_ID,
                            'up_down' => 'down']) . "';",
                        'title' => Yii::t('app', 'Move down'),
                        'class'=>'btn btn-primary btn-xs',
                        'disabled' => !$noodEnvelop->higherOrderNumberExists($model->nood_envelop_ID),
                    ]
                ); ?>
            </p>

            <b>
            <?php echo Html::encode($model->getAttributeLabel('nood_envelop_volgorde')); ?>
            </b>
            <?php echo Html::encode($model->nood_envelop_volgorde); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('coordinaat')); ?>
            </b>
            <?php echo Html::encode($model->coordinaat); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('score')); ?>
            </b>
            <?php echo Html::encode($model->score); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('opmerkingen')); ?>
            </b>
            <p class="max-route-items-height">
                <?php echo Html::encode($model->opmerkingen); ?></br>
            </p>
        </div>
    </div>
</div>
