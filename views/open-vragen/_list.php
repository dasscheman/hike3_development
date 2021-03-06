<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\widgets\AlertBlock;
use app\models\OpenVragen;

/* @var $this GroupsController */
/* @var $data Groups */
$openVragen = new OpenVragen();
?>
<div class="col-sm-3">
    <div class="row-1">
        <div class="view">
            <?php
                Pjax::begin(['id' => 'open-vragen-list-' . $model->open_vragen_ID, 'enablePushState' => false]);
                echo AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => 4000,
                ]);
            ?>

            <p>
                <h3>
                    <?php echo Html::encode($model->open_vragen_name); ?> </br>
                </h3>
                <?php
                     echo ButtonAjax::widget([
                        'name' => Yii::t('app', 'Edit question'),
                        'route' => ['/open-vragen/update', 'open_vragen_ID' => $model->open_vragen_ID],
                        'modalId' => '#main-modal',
                        'modalContent'=>'#main-content-modal',
                        'options' => [
                            'class' => 'btn btn-xs btn-success',
                            'title' => Yii::t('app', 'Edit question'),
                            'disabled' => !Yii::$app->user->can('organisatie'),
                        ]
                    ]);
                    ?> </br> <?php

                    echo Html::Button(
                        '<span class="glyphicon glyphicon-chevron-left"></span>',
                        [
                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                                '/open-vragen/move-up-down',
                                'vraag_id' => $model->open_vragen_ID,
                                'up_down' => 'up']) . "';",
                            'title' => Yii::t('app', 'Move up'),

                            'class'=>'btn btn-primary btn-xs',
                            'disabled' => !$openVragen->lowererOrderNumberExists($model->open_vragen_ID),
                        ]
                    );

                    echo Html::Button(
                        '<span class="glyphicon glyphicon-chevron-right"></span>',
                        [
                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl([
                                '/open-vragen/move-up-down',
                                'vraag_id' => $model->open_vragen_ID,
                                'up_down' => 'down']) . "';",
                            'title' => Yii::t('app', 'Move down'),
                            'class'=>'btn btn-primary btn-xs',
                            'disabled' => !$openVragen->higherOrderNumberExists($model->open_vragen_ID),
                        ]
                    );
                ?>
            </p>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('vraag_volgorde')); ?>
            </b>
            <?php echo Html::encode($model->vraag_volgorde); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>
            </b>
            <?php echo Html::encode($model->omschrijving); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('vraag')); ?>
            </b>
            <?php echo Html::encode($model->vraag); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('goede_antwoord')); ?>
            </b>
            <?php echo Html::encode($model->goede_antwoord); ?></br>
            <b>
            <?php echo Html::encode($model->getAttributeLabel('score')); ?>
            </b>
            <?php echo Html::encode($model->score); ?></br>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
