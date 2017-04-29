<?php

use app\models\Route;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\models\ActivityFeed;
use kartik\widgets\AlertBlock;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');

// $bordered = TRUE;
// $striped = TRUE;
// $condensed = TRUE;
// $responsive = FALSE;
// $hover = TRUE;
// $pageSummary = FALSE;
// $heading = FALSE;
// $exportConfig = TRUE;
// $responsiveWrap = FALSE;
?>

<div class="organisatie-overview">
    <div class="container text-center">
        <div class="row">
            <div class="col-sm-3 well">
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
                    <?php echo Html::encode($groupModel->time_walking); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('time_left')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->time_left); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('bonus_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->bonus_score); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('post_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->post_score); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('vragen_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->vragen_score); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('hint_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->hint_score); ?></br>
                    <b>
                    <?php echo Html::encode($groupModel->getAttributeLabel('total_score')); ?>:
                    </b>
                    <?php echo Html::encode($groupModel->total_score); ?></br>

                </div>
                <div class="well">
                    <?php echo Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-dashboard', ['model'=>$questionsData]); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        Modal::begin(['id'=>'main-modal']);
                        echo '<div id="main-content-modal"></div>';
                        Modal::end();
                        echo AlertBlock::widget([
                            'type' => AlertBlock::TYPE_ALERT,
                            'useSessionFlash' => true,
                            'delay' => 4000,
                        ]);
                        ?>
                    </div>
                </div>
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
            <div class="col-sm-3 well">
                <div class="well">
                    <?php echo Yii::$app->controller->renderPartial('/open-nood-envelop/view-dashboard', ['model' => $hintsData]); ?>
                </div>
                <div class="well">
                    <?php echo Yii::$app->controller->renderPartial('/qr-check/view-dashboard', ['model' => $qrCheckData]); ?>
                </div>
                <div class="well">
                    <?php echo Yii::$app->controller->renderPartial('/bonuspunten/view-dashboard', ['model' => $bonusData]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
