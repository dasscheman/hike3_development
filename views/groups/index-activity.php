<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Activity groups');
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
    $responsiveWrap = FALSE;
    
    $gridColumns = [
        'groupname',
        'username',
        'timestamp',
        'source',
        'title',
        'description',
        'score',
    ];

    echo GridView::widget([
        'dataProvider' => $activityFeed,
        'filterModel' => $activityFeed,
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
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'responsiveWrap' => $responsiveWrap,
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
