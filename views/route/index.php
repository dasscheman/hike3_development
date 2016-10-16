<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use app\models\Route;
use yii\bootstrap\Tabs;
use app\models\OpenVragen;
use app\models\Qr;
use app\models\NoodEnvelop;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\RouteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Routes');
?>

<div class="tbl-route-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    Modal::begin(['id'=>'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    $count=0;
    $gridColumns = [
        [
            'header' => 'View Qr',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => 'LALAAL',
            'collapseTitle' => 'LOLOLO',
        ],
        'event_ID' =>
        [
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=>function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('view', ['model' => $model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => 'LALAAL',
            'collapseTitle' => 'LOLOLO',
        ],
        [
            'header' => '#Vragen',
            'value' => function($key){
                return Route::findOne($key)->getOpenVragenCount();
            },
        ],
        [
            'header' => '#Hints',
            'value' => function($key){
                return Route::findOne($key)->getNoodEnvelopCount();
            },
        ],
        [
            'header' => '#Stille posten',
            'value' => function($key){
                return Route::findOne($key)->getQrCount();
            },
        ],
        [
            'header' => 'Aangemaakt',
            'value' => function($key){
                return Route::findOne($key)->getCreateUser()->username;
            },
        ],
        [
            'header' => 'Laatst Bijgewerkt',
            'value' => function($key){
                return Route::findOne($key)->getUpdateUser()->username;
            },

        ],
        'route_name',
        'day_date',
        'route_volgorde'
    ];
    $bordered = FALSE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = TRUE;
    $exportConfig = TRUE;

    while(strtotime($startDate) <= strtotime($endDate)) {
        $dataArray[$count]=array(
		    'label' =>$startDate,
		    'content' => GridView::widget([
                'id' => 'kv-grid-demo',
                'dataProvider'=>$searchModel->search(['RouteSearch' => ['day_date' => $startDate]]),
                'columns'=>$gridColumns,
                'containerOptions'=>FALSE, //['style'=>'overflow: auto'], // only set when $responsive = false
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                'pjax'=>true, // pjax is set to always true for this demo
                // set your toolbar
                'toolbar'=> [
                    ['content'=>
                        ButtonAjax::widget([
                            'name'=>'Create',
                            'route'=>['route/create'],
                            'modalId'=>'#main-modal',
                            'modalContent'=>'#main-content-modal',
                            'options'=>[
                                'class'=>'btn btn-success',
                                'title'=>'Button for create application',
                            ]
                        ]),
                    ],
                    '{export}',
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
