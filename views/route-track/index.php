<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
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

$this->title = Yii::t('app', 'Tracks');
?>
<div class="track-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $gridColumns = [
        'name',
        'elevation',
        'latitude',
        'longitude',
        'timestamp',
//        'latitude',
            //'longitude',
            //'accuracy',
            //'timestamp:datetime',
            //'create_time',
            //'create_user_ID',
            //'update_time',
            //'update_user_ID',
//        [
//            'attribute' => 'group_name',
//            'value' => 'group.group_name'
//        ],
//        'date',
//        [
//            'attribute' => 'post_name',
//            'value' => 'post.post_name',
//        ],
//        'omschrijving',
//        'score',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'id' => $model->route_track_ID],
                        [
                            'class' => '',
                            'data' => [
                                'confirm' => 'Are you absolutely sure ? You will lose all gps information for this event.',
                                'method' => 'post',
                            ],
                        ]
                    );
                },
            ],
        ]
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => $gridColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax' => true, // pjax is set to always true for this demo
        'toolbar' => false,
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
