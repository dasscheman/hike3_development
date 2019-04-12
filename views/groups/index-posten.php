<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overzicht van gepasserde posten');
?>
<div class="groups-index-posten">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $bordered = TRUE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = FALSE;
    $footer = FALSE;
    $exportConfig = FALSE;
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
            'expandTitle' => Yii::t('app', 'Open'),
            'collapseTitle' => Yii::t('app', 'Sluit'),
        ],
        [
            'attribute' => 'rank',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
        ],
        [
            'attribute' => 'total_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
        ],
        [
            'attribute' => 'time_walking',
            'value'=> function ($model, $key, $index, $column) {
                return Yii::$app->setupdatetime->displayFormat($model->time_walking, 'time', true, true);
            },
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
        ],
        [
            'attribute' => 'time_left',
            'value'=> function ($model, $key, $index, $column) {
                return Yii::$app->setupdatetime->displayFormat($model->time_left, 'time', true, true);
            },
            'visible'=> isset($model->event->max_time)?true:false,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
        ],
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/post-passage/view-group', ['model'=>$model]);
            },
            'allowBatchToggle' => FALSE,
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandAllTitle' => Yii::t('app', 'Open posten overzicht'),
            'expandTitle' => Yii::t('app', 'Open posten overzicht'),
            'collapseTitle' => Yii::t('app', 'Sluit posten overzicht'),
        ],
    ];


    echo GridView::widget([
        'id' => 'kv-grid-posts',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class'=>'kartik-sheet-style'],
        'filterRowOptions' => ['class'=>'kartik-sheet-style'],
        'resizableColumns' => $resizableColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'responsiveWrap' => $responsiveWrap,
        'pjax' => TRUE, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar'=> [
//            '{export}',
//            '{toggleData}',
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
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'responsiveWrap' => $responsiveWrap,
        'hover' => $hover,
        'showPageSummary' => $pageSummary,
        'panel' => [
            'type' => GridView::TYPE_INFO,
            'heading' => $heading,
            'footer' => $footer
        ],
        'persistResize' => false,
        'exportConfig' => $exportConfig,
    ]); ?>

</div>
