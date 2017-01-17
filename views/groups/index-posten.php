<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ExpandRowColumn;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview passed stations and bonuspoints');
?>
<div class="groups-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    $bordered = FALSE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = FALSE;
    $exportConfig = TRUE;
    $resizableColumns = FALSE;

    $gridColumns = [
        'group_name',
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/users/view_groups', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'allowBatchToggle' => FALSE,
//            'expandIcon' => 'group_name',
            'expandTitle' => Yii::t('app', 'Open view users'),
            'collapseTitle' => Yii::t('app', 'Close view users'),
        ],
        [
            'attribute' => 'rank',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'time_walking',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'time_left',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/post-passage/view-groups', ['model'=>$model]);
            },
            'allowBatchToggle' => FALSE,
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandAllTitle' => Yii::t('app', 'Open all view stations'),
            'expandTitle' => Yii::t('app', 'Open view stations'),
            'collapseTitle' => Yii::t('app', 'Close view stations'),
        ],
      //  [
      //      'header' => Yii::t('app', '#Hints'),
      //      'value' => function($key){
      //          return Route::findOne($key)->getNoodEnvelops();
      //      },
      //  ],
        [
            'attribute' => 'bonus_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/bonuspunten/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'allowBatchToggle' => FALSE,
            'expandTitle' => Yii::t('app', 'Open view bonuspoints'),
            'collapseTitle' => Yii::t('app', 'Close view bonuspoints'),
        ],
//        [
//            'header' => Yii::t('app', '#Silent posts'),
//            'value' => function($key){
//                return Route::findOne($key)->getQrCount();
//            },
//        ],
//        [
//            'class' => 'yii\grid\ActionColumn',
//            'header'=>'Actions',
//            'template' => '{up} {down}',
//            'buttons' => [
//                'up' => function ($url, $model) {
//                    return Html::a(
//                        '<span class="glyphicon glyphicon-chevron-up"></span>',
//                        [
//                            'route/move-up-down',
//                            'id' => $model->route_ID,
//                            'up_down' => 'up',
//                        ],
//                        [
//                            'title' => Yii::t('app', 'Move up'),
//                            'class'=>'btn btn-primary btn-xs',
//                        ]
//                    );
//                },
//                'down' => function ($url, $model) {
//                    return Html::a(
//                        '<span class="glyphicon glyphicon-chevron-down"></span>',
//                        [
//                            'route/move-up-down',
//                            'id' => $model->route_ID,
//                            'up_down' => 'down',
//                        ],
//                        [
//                            'title' => Yii::t('app', 'Mode down'),
//                            'class'=>'btn btn-primary btn-xs',
//                        ]
//                    );
//                },
//            ],
//            'visibleButtons' => [
//                'up' => function ($model, $key, $index) {
//                    return Yii::$app->user->identity->isActionAllowed('route', 'moveUpDown', [$key], ['move_action' => 'up', 'date' => $model->day_date]);
//                 },
//                'down' => function ($model, $key, $index) {
//                    return Yii::$app->user->identity->isActionAllowed('route', 'moveUpDown', [$key], ['move_action' => 'down', 'date' => $model->day_date]);
//                 }
//            ]
//        ],
    ];
//
//    foreach ($dataProvider->models as $temp_model) {
//        $temp_model->setRank();
//    }
//            d($dataProvider->models[0]->setRank());
//            dd($dataProvider->models[0]->getRank());


    echo GridView::widget([
        'id' => 'kv-grid-posts',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class'=>'kartik-sheet-style'],
        'filterRowOptions' => ['class'=>'kartik-sheet-style'],
        'resizableColumns' => $resizableColumns,
        'pjax' => TRUE, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export' => [
            'fontAwesome'=>true
        ],
        // parameters from the demo form
        'bordered' => $bordered,
        'striped' => $striped,
        'condensed' => $condensed,
        'responsive' => $responsive,
        'hover' => $hover,
        'showPageSummary' => $pageSummary,
        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => $heading,
        ],
        'persistResize' => false,
        //'exportConfig' => $exportConfig,
    ]); ?>

</div>
