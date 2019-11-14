<?php

use app\models\Route;
use kartik\grid\GridView;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Routes');
?>

<div class="route-index">

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
    $resizableColumns = false;
    $responsiveWrap = false;

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
            'attribute' => 'route_name',
            'format' => 'raw',
           // here comes the problem - instead of parent_region I need to have parent
            'value'=>function ($model, $key, $index, $column) {
                $label = $model->route_name;
                if(isset($model->start_datetime) ||
                  isset($model->end_datetime)){
                    $label = $model->route_name  .
                        ' (' .
                        Yii::$app->setupdatetime->displayFormat($model->start_datetime, 'datetime_short', false, false)
                        . ' - ' .
                        Yii::$app->setupdatetime->displayFormat($model->end_datetime, 'datetime_short', false, false) .')';

                }
                return ButtonAjax::widget([
                    'name' => $label,
                     'route' => ['route/update', 'route_ID' => $key],
                     'modalId'=>'#main-modal',
                     'modalContent'=>'#main-content-modal',
                     'options'=>[
                         'class'=> 'btn btn-xs btn-primary',
                         'title'=>'Edit',
                         'disabled' => !Yii::$app->user->can('organisatie'),
                     ]
                 ]);
            },
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-vragen/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'allowBatchToggle' => false,
            'expandTitle' => Yii::t('app', 'Open view questions'),
            'collapseTitle' => Yii::t('app', 'Close view questions'),
        ],
        [
            'header' => Yii::t('app', '#Questions'),
            'value' => function ($model, $key) {
                return Route::findOne($key)->getOpenVragenCount();
            },
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/nood-envelop/view', ['model'=>$model]);
            },
            'allowBatchToggle' => false,
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandAllTitle' => Yii::t('app', 'Open all view hints'),
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],
        [
            'header' => Yii::t('app', '#Hints'),
            'value' => function ($model, $key) {
                return Route::findOne($key)->getNoodEnvelopCount();
            },
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/qr/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'allowBatchToggle' => false,
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],
        [
            'header' => Yii::t('app', '#Silent posts'),
            'value' => function ($model, $key) {
                return Route::findOne($key)->getQrCount();
            },
        ],
        'route_volgorde',
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{up} {down} {routebook}',
            'buttons' => [
                'up' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-chevron-up"></span>',
                        [
                            'route/move-up-down',
                            'route_ID' => $model->route_ID,
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
                            'route/move-up-down',
                            'route_ID' => $model->route_ID,
                            'up_down' => 'down',
                        ],
                        [
                            'title' => Yii::t('app', 'Mode down'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
                'routebook' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-road"></span>',
                        [
                            'routebook/update',
                            'route_ID' => $model->route_ID
                        ],
                        [
                            'title' => Yii::t('app', 'Bewerk routeboek'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
            ],
            'visibleButtons' => [
                'up' => function ($model, $key, $index) {
                    $route = new Route();
                    if (Yii::$app->user->can('organisatie') &&
                        $route->lowerOrderNumberExists($model->route_ID)) {
                        return true;
                    }
                    return false;
                },
                'down' => function ($model, $key, $index) {
                    $route = new Route();
                    if (Yii::$app->user->can('organisatie') &&
                        $route->higherOrderNumberExists($model->route_ID)) {
                        return true;
                    }
                    return false;
                }
            ]
        ],
    ];

    echo GridView::widget([
        'id' => 'kv-grid-route',
        'responsiveWrap' => $responsiveWrap,
        'dataProvider'=>$searchModel->searchRouteInEvent([]),
        'columns'=>$gridColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'resizableColumns' => $resizableColumns,
        'pjax'=>true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [
            ['content'=>
                ButtonAjax::widget([
                    'name'=> Yii::t('app', 'Add route item'),
                    'route'=>['route/create'],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-success',
                        'title'=>Yii::t('app', 'Create new route item'),
                        'disabled' => !Yii::$app->user->can('organisatieOpstart'),
                    ]
                ]),
            ],
        ],
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
    ]);
    ?>
</div>
