<?php
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\helpers\Url;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;


use dosamigos\qrcode\QrCode;
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
            <h3>
                <?php echo Html::encode($model->qr_name); ?> </br>
            </h3>
                <?php
                 echo ButtonAjax::widget([
                    'name'=> Yii::t('app', 'Modify silent station'),
                    'route'=>['qr/update', 'id' => $model->qr_ID],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-xs btn-success',
                        'title'=> Yii::t('app', 'Modify silent station'),
                    ]
                ]);?>
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
            <?php Pjax::end();

            echo Html::a(
                Yii::t('app', 'Create pdf file'),
                ['/qr/report', 'id' => $model->qr_ID],
                [
                    'class' => 'btn btn-xs btn-primary',
                    'target'=>'_blank',
                    'data-pjax' => "0"
                ]
            ); ?></br>
            <?php echo Html::img(Url::to(['qr/qrcode', 'qr_code' => $model->qr_code, 'event_id' => $model->event_ID]));?>


        </div>
    </div>
</div>
