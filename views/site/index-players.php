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

$bordered = TRUE;
$striped = TRUE;
$condensed = TRUE;
$responsive = FALSE;
$hover = TRUE;
$pageSummary = FALSE;
$heading = FALSE;
$exportConfig = TRUE;
$responsiveWrap = FALSE;

$attributes = [
    [
        'columns' => [
            [
                'attribute' => 'group_members',
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%']
            ],
            [
                'attribute' => 'rank',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
                'displayOnly' => true
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'time_walking',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'time_left',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'bonus_score',
//                    'value' => $groupModel,
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'post_score',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'qr_score',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'vragen_score',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'hint_score',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'total_score',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
];

?>

<div class="organisatie-overview">
    <div class="container text-center">
        <div class="row">
            <div class="col-sm-3 well">
                <div class="well">
                    <h3><?php echo  $groupModel->group_name ?></h3>
                </div>
                <div class="well">
                    <?php echo Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view-dashboard', ['model'=>$questionsData]); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default text-left">
                            <div class="panel-body">
                                <?php
                                Modal::begin(['id'=>'main-modal']);
                                echo '<div id="main-content-modal"></div>';
                                Modal::end();
                                echo AlertBlock::widget([
                                    'type' => AlertBlock::TYPE_ALERT,
                                    'useSessionFlash' => true,
                                    'delay' => 4000,
                                ]);
                                // View file rendering the widget
                                echo DetailView::widget([
                                    'model' => $groupModel,
                                    'attributes' => $attributes,
                                    'mode' => 'view',
                                    'bordered' => $bordered,
                                    'striped' => $striped,
                                    'condensed' => $condensed,
                                    'responsive' => $responsive,
                                    'hover' => $hover,
                                //        'hAlign'=>$hAlign,
                                //        'vAlign'=>$vAlign,
                                //        'fadeDelay'=>$fadeDelay,
                                    'deleteOptions' => [ // your ajax delete parameters
                                        'params' => ['id' => 1000, 'kvdelete' => true],
                                    ],
                                    'container' => ['id' => 'kv-demo'],
                                    'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
                                ]);
                                ?>
                            </div>
                        </div>
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
                  'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
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
