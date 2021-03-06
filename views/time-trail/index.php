<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\TimeTrailItem;
use app\models\Groups;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TimeTrailItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Time Trails');
?>

<div class="time-trail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $bordered = false;
    $striped = true;
    $condensed = true;
    $responsive = false;
    $hover = true;
    $pageSummary = false;
    $heading = false;
    $exportConfig = true;
    $responsiveWrap = false;
    $dataArray = [];

    Modal::begin(['id'=>'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    echo CustomAlertBlock::widget([
        'type' => CustomAlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 20000,
    ]);

    $count=0;
    $gridColumns = [
        [
            'attribute' => 'time_trail_item_name',
        ],
        [
            'header' => Yii::t('app', 'View time trail code'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/time-trail-item/view', ['time_trail_item_id'=>$key, 'model' => $model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'allowBatchToggle' => false,
            'expandTitle' => Yii::t('app', 'Open view time trail code'),
            'collapseTitle' => Yii::t('app', 'Close view time trail code'),
        ],
        [
            'header' => Yii::t('app', 'View checked'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $groups = Groups::find()
                    ->where('event_ID =:event_id')
                    ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                    ->all();
                return Yii::$app->controller->renderPartial('/time-trail-check/view-groups', ['time_trail_item_id'=>$key, 'groups' => $groups]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open view groups'),
            'collapseTitle' => Yii::t('app', 'Close view groups'),
        ],
        'score',
        [
            'header' => Yii::t('app', '#groups passed'),
            'value' => function ($model, $key, $index, $column) {
                return TimeTrailItem::findOne($key)->getTimeTrailChecksCount();
            },
        ],
        'volgorde',
        'max_time',
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{up} {down} {pdf}',
            'buttons' => [
                'up' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-chevron-up"></span>',
                        [
                            'time-trail-item/move-up-down',
                            'time_trail_item_ID' => $model->time_trail_item_ID,
                            'up_down' => 'up',
                        ],
                        [
                            'title' => Yii::t('app', 'Move up'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
                'down' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-chevron-down"></span>',
                        [
                            'time-trail-item/move-up-down',
                            'time_trail_item_ID' => $model->time_trail_item_ID,
                            'up_down' => 'down',
                        ],
                        [
                            'title' => Yii::t('app', 'Mode down'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
                'pdf' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-print"></span>',
                        [
                            'time-trail-item/print-pdf',
                            'time_trail_item_ID' => $model->time_trail_item_ID,
                        ],
                        [
                            'title' => Yii::t('app', 'Get pdf'),
                            'class'=>'btn btn-primary btn-xs',
                            'target'=>'_blank',
                            'data-pjax' => "0"
                        ]
                    );
                },
            ],
            'visibleButtons' => [
                'up' => function ($model, $key, $index) {
                    $timeTrailItem = new TimeTrailItem();
                    if (Yii::$app->user->can('organisatie') &&
                        $timeTrailItem->lowererOrderNumberExists($model->time_trail_item_ID)) {
                        return true;
                    }
                    return false;
                },
                'down' => function ($model, $key, $index) {
                    $timeTrailItem = new TimeTrailItem();
                    if (Yii::$app->user->can('organisatie') &&
                        $timeTrailItem->higherOrderNumberExists($model->time_trail_item_ID)) {
                        return true;
                    }
                    return false;
                }
            ]
        ],
    ];
    foreach ($model as $key => $item) {
        $dataArray[$count]=array(
            'label' =>$item->time_trail_name,
            'active' => $item->time_trail_ID == Yii::$app->getRequest()->getCookies()->getValue('time_trail_tab')? true: false,
            'content' => GridView::widget([
                'id' => 'kv-grid-' . $item->time_trail_ID, //'kv-grid-demo',
                'dataProvider' => $searchModel->search(['TimeTrailItemSearch' => ['time_trail_ID' => $item->time_trail_ID]]),
                'columns' => $gridColumns,
                'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                'responsiveWrap' => $responsiveWrap,
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                'pjax'=>true, // pjax is set to always true for this demo
                // set your toolbar
                'toolbar'=> [
                [
                    'content'=>
                        ButtonAjax::widget([
                            'name'=> Yii::t('app', 'Edit time trail'),
                            'route'=>['time-trail/update', 'time_trail_ID' => $item->time_trail_ID],
                            'modalId'=>'#main-modal',
                            'modalContent'=>'#main-content-modal',
                            'options'=>[
                                'class' => 'btn btn-primary',
                                'title' => Yii::t('app', 'Update time trail'),
                                'disabled' => !Yii::$app->user->can('organisatie'),
                            ]
                        ]),
                    ],
                ],
                // parameters from the demo form
                'bordered'=>$bordered,
                'striped'=>$striped,
                'condensed'=>$condensed,
                'responsive'=>$responsive,
                'hover'=>$hover,
                'showPageSummary'=>$pageSummary,
                'panel'=>[
                    'type'=>GridView::TYPE_INFO,
                    'heading'=>$heading,
                ],
                'persistResize'=>false,
            ])
        );
        $count++;
    }
    echo Tabs::widget([
        'items' => $dataArray
    ]);
    ?>
</div>
