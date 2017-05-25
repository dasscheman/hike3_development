<?php

use app\models\Route;
use kartik\grid\GridView;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use app\components\CustomAlertBlock;
use yii\web\Cookie;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Routes');
?>

<div class="route-index">

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
    $responsiveWrap = FALSE;

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
                return ButtonAjax::widget([
                    'name'=>$model->route_name,
                     'route'=>['route/update', 'route_ID' => $key],
                     'modalId'=>'#main-modal',
                     'modalContent'=>'#main-content-modal',
                     'options'=>[
                         'class'=> 'btn btn-xs btn-primary',
                         'title'=>'Edit',
                         'disabled' => !Yii::$app->user->identity->isActionAllowed('route', 'update', ['route_ID' => $key]),
                     ]
                 ]);
            },
        ],
        'route_volgorde',
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
            'allowBatchToggle' => FALSE,
            'expandTitle' => Yii::t('app', 'Open view questions'),
            'collapseTitle' => Yii::t('app', 'Close view questions'),
        ],
        [
            'header' => Yii::t('app', '#Questions'),
            'value' => function($model, $key){
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
            'allowBatchToggle' => FALSE,
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandAllTitle' => Yii::t('app', 'Open all view hints'),
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],
        [
            'header' => Yii::t('app', '#Hints'),
            'value' => function($model, $key){
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
            'allowBatchToggle' => FALSE,
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],
        [
            'header' => Yii::t('app', '#Silent posts'),
            'value' => function($model, $key){
                return Route::findOne($key)->getQrCount();
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{up} {down}',
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
            ],
            'visibleButtons' => [
                'up' => function ($model, $key, $index) {
                    return Yii::$app->user->identity->isActionAllowed(
                        'route',
                        'moveUpDown',
                        ['route_ID' => $key],
                        ['move_action' => 'up', 'date' => $model->day_date]);
                 },
                'down' => function ($model, $key, $index) {
                    return Yii::$app->user->identity->isActionAllowed(
                        'route',
                        'moveUpDown',
                        ['route_ID' => $key],
                        ['move_action' => 'down', 'date' => $model->day_date]);
                 }
            ]
        ],
    ];

    $dataArray[$count]=array(
        'label' => Yii::t('app', 'Introduction'),
        'active' => '0000-00-00' === Yii::$app->getRequest()->getCookies()->getValue('route_day_tab')? TRUE: FALSE,
        'options' => ['id' => 'Introduction'],
        'content' => GridView::widget([
            'id' => 'kv-grid-0000-00-00',
            'dataProvider'=>$searchModel->searchRouteInEvent(['RouteSearch' => ['day_date' => '0000-00-00']]),
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
                        'name'=>'Create',
                        'route'=>['route/create', 'date' => '0000-00-00'],
                        'modalId'=>'#main-modal',
                        'modalContent'=>'#main-content-modal',
                        'options'=>[
                            'class'=>'btn btn-success',
                            'title'=>Yii::t('app', 'Create new route item'),
                            'disabled' => !Yii::$app->user->identity->isActionAllowed('route', 'create'),
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

    while(strtotime($startDate) <= strtotime($endDate)) {
        $dataArray[$count]=array(
		    'label' =>$startDate,
            'active' => $startDate === Yii::$app->getRequest()->getCookies()->getValue('route_day_tab')? TRUE: FALSE,
            'options' => ['id' => $startDate],
            'responsiveWrap' => $responsiveWrap,
		    'content' => GridView::widget([
                'id' => 'kv-grid-' . $startDate, //'kv-grid-demo',
                'dataProvider'=>$searchModel->searchRouteInEvent(['RouteSearch' => ['day_date' => $startDate]]),
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
                            'name'=>'Create',
                            'route'=>['route/create', 'date' => $startDate],
                            'modalId'=>'#main-modal',
                            'modalContent'=>'#main-content-modal',
                            'options'=>[
                                'class'=>'btn btn-success',
                                'title'=>'Button for create application',
                                'disabled' => !Yii::$app->user->identity->isActionAllowed('route', 'create'),
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
            ])
        );
		$startDate = date('Y-m-d', strtotime($startDate. ' + 1 days'));
	    $count++;
		// more then 10 days is unlikly, therefore break.
		if ($count == 10) {
			break;
		}
	}
    echo Tabs::widget([
        'items' => $dataArray
    ]);
    ?>
</div>
