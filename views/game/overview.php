<?php

use app\models\Route;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

$bordered = FALSE;
$striped = TRUE;
$condensed = TRUE;
$responsive = FALSE;
$hover = TRUE;
$pageSummary = FALSE;
$heading = FALSE;
$exportConfig = TRUE;
$responsiveWrap = FALSE;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');
?>

<div class="route-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    Modal::begin(['id'=>'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    $count=0;
    $gridColumns = [
        'route_name',
        'route_volgorde',
        [
            'header' => Yii::t('app', 'View Questions'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
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
            'header' => Yii::t('app', 'View hints'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-nood-envelop/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
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
            'header' => Yii::t('app', 'View silent posts'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/qr-check/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],

        // Aantal stille posten moet verborgen blijven. Misschien moet er komen
        // te staan hoeveel er gevonden zijn. Voor nu helemaal weg laten om onduidelijkheden te voorkomen.
        // [
        //
        //     'header' => Yii::t('app', '#Silent posts'),
        //     'value' => function($model, $key){
        //         return Route::findOne($key)->getQr();
        //     },
        // ],
    ];

    $dataArray[$count]=array(
        'label' => Yii::t('app', 'Introduction'),
        'content' => GridView::widget([
            'id' => 'kv-grid-0000-00-00',
            'dataProvider'=>$searchRouteModel->search(['RouteSearch' => ['day_date' => '0000-00-00']]),
            'columns'=>$gridColumns,
            'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            'pjax'=>TRUE, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar'=> [
                '{toggleData}',
            ],
            // set export properties
            'export'=>[
                'fontAwesome'=>true
            ],
            // parameters from the demo form
            'bordered'=>$bordered,
            'striped'=>$striped,
            'condensed'=>$condensed,
            'responsive'=>$responsive,
            'responsiveWrap' => $responsiveWrap,
            'hover'=>$hover,
            'showPageSummary'=>$pageSummary,
            'panel'=>[
                'type'=>GridView::TYPE_INFO,
                'heading'=>$heading,
            ],
            'persistResize'=>false,
            'exportConfig'=>$exportConfig,
        ])
    );
    $count++;

    while(strtotime($startDate) <= strtotime($endDate)) {
        $dataArray[$count]=array(
		    'label' =>$startDate,
		    'content' => GridView::widget([
                'id' => 'kv-grid-' . $startDate, //'kv-grid-demo',
                'dataProvider'=>$searchRouteModel->search(['RouteSearch' => ['day_date' => $startDate]]),
                'columns'=>$gridColumns,
                'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                'pjax'=>true, // pjax is set to always true for this demo
                // set your toolbar
                'toolbar'=> [
                    '{toggleData}',
                ],
                // set export properties
                'export'=>[
                    'fontAwesome'=>true
                ],
                // parameters from the demo form
                'bordered'=>$bordered,
                'striped'=>$striped,
                'condensed'=>$condensed,
                'responsive'=>$responsive,
                'responsiveWrap' => $responsiveWrap,
                'hover'=>$hover,
                'showPageSummary'=>$pageSummary,
                'panel'=>[
                    'type'=>GridView::TYPE_INFO,
                    'heading'=>$heading,
                ],
                'persistResize'=>false,
                //'exportConfig'=>$exportConfig,
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
