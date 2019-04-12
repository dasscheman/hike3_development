<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ListView;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');

?>
<div class="site-index-players">
    <div class="container text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-sm-3 well">
                <?php
                echo CustomAlertBlock::widget([
                    'type' => CustomAlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => FALSE,
                ]);

                Modal::begin(['id'=>'main-modal']);
                echo '<div id="main-content-modal"></div>';
                Modal::end();
                ?>

                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        echo ListView::widget([
                            'summary' => FALSE,
                            'pager' => FALSE,
                            'dataProvider' => $timeTrailCheckDataLastItem,
                            'itemView' => '/time-trail/_list',
                            'emptyText' => FALSE,
                        ]);
                        ?>
                    </div>
                </div>

                <div class="well">
                    <h3><?php echo  $groupModel->group_name ?></h3>
                    <?php echo '(' . Html::encode($groupModel->group_members) . ')'; ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('rank')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->rank); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('time_walking')); ?>:
                    </b>
                    <?php
                    echo Html::encode(Yii::$app->setupdatetime->displayFormat($groupModel->time_walking, 'time', true, true)); ?> </br>
                    <b>
                    <?php
                    if(isset($groupModel->event->max_time)) {
                        echo Html::encode($groupModel->getAttributeLabel('time_left')); ?>:
                        </b>
                        <?php echo Html::encode(Yii::$app->setupdatetime->displayFormat($groupModel->time_left, 'time', TRUE)); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->qr_score > 0) {
                        echo Html::encode($groupModel->getAttributeLabel('qr_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->qr_score); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->bonus_score > 0) {
                        echo Html::encode($groupModel->getAttributeLabel('bonus_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->bonus_score); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->post_score > 0) {
                        echo Html::encode($groupModel->getAttributeLabel('post_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->post_score); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->vragen_score > 0) {
                        echo Html::encode($groupModel->getAttributeLabel('vragen_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->vragen_score); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->hint_score != 0) {
                        echo Html::encode($groupModel->getAttributeLabel('hint_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->hint_score); ?></br>
                        <b>
                    <?php
                    }
                    if($groupModel->trail_score > 0) {
                        echo Html::encode($groupModel->getAttributeLabel('trail_score')); ?>:
                        </b>
                        <?php echo Html::encode($groupModel->trail_score); ?></br>
                        <b>
                    <?php
                    }
                    echo Html::encode($groupModel->getAttributeLabel('total_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->total_score); ?></br>

                </div>
                <?php
                if ($questionsData->totalCount > 0) { ?>
                    <div class="well">
                        <?php echo Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-vraag', ['model'=>$questionsData]); ?>
                    </div>
                <?php
                }
                if ($openHintsData->totalCount > 0) { ?>
                    <div class="well">
                      <?php
                      ?>
                        <?php echo Yii::$app->controller->renderPartial('/open-nood-envelop/view-dashboard-open', ['model' => $openHintsData]); ?>
                    </div>
                <?php
                }
                if ($closedHintsData->totalCount > 0) { ?>
                    <div class="well">
                      <?php
                      ?>
                        <?php echo Yii::$app->controller->renderPartial('/nood-envelop/view-dashboard-closed', ['model' => $closedHintsData]); ?>
                    </div>
                <?php
                } ?>
            </div>
            <?php
            if(!Yii::$app->devicedetect->isMobile()) { ?>
                <div class="col-sm-6">
                    <?php
                    echo ListView::widget([
                      'summary' => FALSE,
                      'pager' => [
                          'prevPageLabel' => Yii::t('app', 'previous'),
                          'nextPageLabel' => Yii::t('app', 'next'),
                          'maxButtonCount' => 3,
                          'options' => [
                             'class' => 'pagination pagination-sm',
                          ],
                      ],
                      'dataProvider' => $activityFeed,
                      'itemView' => '/groups/_list-feed',
                      'emptyText' => 'Er is nog geen activiteit bij dit profiel.',
                    ]);
                    ?>
                </div>
            <?php }
            if($timeTrailCheckData->totalCount > 0 ||
                $answerData->totalCount > 0 ||
                $qrCheckData->totalCount > 0 ||
                $bonusData->totalCount > 0) { ?>

                <div class="col-sm-3 well">
                    <?php
                    if ($timeTrailCheckData->totalCount > 0) { ?>
                        <div class="well">
                            <?php echo Yii::$app->controller->renderPartial('/time-trail/view-dashboard', ['model' => $timeTrailCheckData]); ?>
                        </div>
                    <?php
                    }
                    if ($answerData->totalCount > 0) { ?>
                        <div class="well">
                            <?php echo Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-antwoord', ['model'=>$answerData]); ?>
                        </div>
                    <?php
                    }
                    if ($qrCheckData->totalCount > 0) { ?>
                        <div class="well">
                            <?php echo Yii::$app->controller->renderPartial('/qr-check/view-dashboard', ['model' => $qrCheckData]); ?>
                        </div>
                    <?php
                    }
                    if ($bonusData->totalCount > 0) { ?>
                        <div class="well">
                            <?php echo Yii::$app->controller->renderPartial('/bonuspunten/view-dashboard', ['model' => $bonusData]); ?>
                        </div>
                    <?php } ?>
                </div>
            <?php
            } ?>
        </div>
    </div>
</div>
