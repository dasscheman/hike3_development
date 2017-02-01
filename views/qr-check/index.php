<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QrCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview checked silent stations');
?>
<div class="qr-check-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php

    $columns =[
        [
        'attribute' => 'qr_name',
        'value' => 'qr.qr_name'
        ],
        [
        'attribute' => 'group_name',
        'value' => 'group.group_name'
        ],
        [
        'attribute' => 'route_name',
        'value' => 'qr.route.route_name'
        ],
        'create_time',
        [
        'attribute' => 'score',
        'value' => 'qr.score'
        ],
        [
        'attribute' => 'username',
        'value' => 'createUser.username'
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
        'summary' => FALSE,
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
