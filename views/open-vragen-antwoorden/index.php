<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OpenVragenAntwoordenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview answered questions');
?>
<div class="open-vragen-antwoorden-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $columns = [
        [
        'attribute' => 'group_name',
        'value' => 'group.group_name'
        ],
        [
        'attribute' => 'route_name',
        'value' => 'openVragen.route.route_name'
        ],
        [
        'attribute' => 'vraag',
        'value' => 'openVragen.vraag'
        ],
        'antwoord_spelers',
        'correct',
        [
        'attribute' => 'score',
        'value' => 'openVragen.score'
        ],
        'create_time',
        [
            'header' => Yii::t('app', 'View details'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-vragen-antwoorden/_form-organisation', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open detail view'),
            'collapseTitle' => Yii::t('app', 'Close detail view'),
        ],
    ];

    $bordered = FALSE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = FALSE;
    $exportConfig = FALSE;

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax' => TRUE, // pjax is set to always true for this demo
        'toolbar' => FALSE,
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
        'exportConfig'=>$exportConfig,
    ]); ?>

</div>
