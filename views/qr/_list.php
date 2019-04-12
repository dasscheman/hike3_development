<?php
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use prawee\widgets\ButtonAjax;
use yii\widgets\Pjax;
use app\models\Qr;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <p>
                <?php
                Pjax::begin(['id' => 'qr-list-' . $model->qr_ID, 'enablePushState' => false]);
                echo AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => 4000,
                ]); ?>
                <h3 class="max-titel-route-items-height">
                    <b>
                      <?php echo Html::encode($model->qr_name); ?>
                    </b>
                </h3>
                <?php
                 echo ButtonAjax::widget([
                    'name'=> Yii::t('app', 'Modify silent station'),
                    'route'=>['qr/update', 'qr_ID' => $model->qr_ID],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-xs btn-success',
                        'title'=> Yii::t('app', 'Modify silent station'),
                        'disabled' => !Yii::$app->user->can('organisatie'),
                    ]
                ]);?></br> <?php

                echo Html::Button(
                    '<span class="glyphicon glyphicon-chevron-left"></span>',
                    [
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                            '/qr/move-up-down',
                            'qr_id' => $model->qr_ID,
                            'up_down' => 'up']) . "';",
                        'title' => Yii::t('app', 'Move up'),

                        'class'=>'btn btn-primary btn-xs',
                        'disabled' => !Qr::lowererOrderNumberExists($model->qr_ID),
                    ]
                );

                echo Html::Button(
                    '<span class="glyphicon glyphicon-chevron-right"></span>',
                    [
                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                            '/qr/move-up-down',
                            'qr_id' => $model->qr_ID,
                            'up_down' => 'down']) . "';",
                        'title' => Yii::t('app', 'Move down'),
                        'class'=>'btn btn-primary btn-xs',
                        'disabled' => !Qr::higherOrderNumberExists($model->qr_ID),
                    ]
                );
                ?> <br> <?php

                echo Html::a(
                    Yii::t('app', 'Create pdf file'),
                    ['/qr/print-pdf', 'qr_ID' => $model->qr_ID],
                    [
                        'class' => 'btn btn-xs btn-primary',
                        'target'=>'_blank',
                        'data-pjax' => "0"
                    ]
                ); ?>
            </p>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('qr_code')); ?>
            </b>
            <?php echo Html::encode($model->qr_code); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('qr_volgorde')); ?>
            </b>
            <?php echo Html::encode($model->qr_volgorde); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('score')); ?>
            </b>
            <?php echo Html::encode($model->score); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('message')); ?>
            </b>
            <p class="max-route-items-height">
                <?php echo Html::encode($model->message); ?></br>
            </p>
            <?php Pjax::end();?>


        </div>
    </div>
</div>
