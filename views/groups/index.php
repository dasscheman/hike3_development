<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\grid\ExpandRowColumn;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview groups scores');
?>
<div class="tbl-groups-index">

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
            'attribute' => 'bonus_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'post_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'qr_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'vragen_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'hint_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
        [
            'attribute' => 'total_score',
            'visible'=> TRUE,
            'filter' => FALSE,
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class'=>'kartik-sheet-style'],
        'filterRowOptions' => ['class'=>'kartik-sheet-style'],
        'resizableColumns' => $resizableColumns,
        'pjax' => true, // pjax is set to always true for this demo
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
