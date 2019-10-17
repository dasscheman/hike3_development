<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Bonuspunten;
use app\models\TimeTrailCheck;
use app\models\PostPassage;
use app\models\OpenNoodEnvelop;
use app\models\OpenVragenAntwoorden;
use app\models\QrCheck;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$bonuspunten = new Bonuspunten();
$postPassage = new PostPassage();
$qrCheck = new QrCheck();
$openVragenAntwoorden = new OpenVragenAntwoorden();
$timeTrailCheck = new TimeTrailCheck();
$openNoodEnvelop = new OpenNoodEnvelop();

$this->title = Yii::t('app', 'Overzicht groepsscores');
?>
<div class="tbl-groups-index">

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

    $gridColumns = [
        'group_name',
        [
            'header'=> '<span class="glyphicon glyphicon-eye-open"></span>',
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
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
            'contentOptions' => ['class' => 'kv-align-center'],
        ],
        [
            'attribute' => 'bonus_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $bonuspunten->anyGroupScoredBonuspunten(),
        ],
        [
            'attribute' => 'post_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $postPassage->anyGroupScoredStation()
        ],
        [
            'attribute' => 'qr_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $qrCheck->anyGroupScoredQr()
        ],
        [
            'attribute' => 'vragen_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $openVragenAntwoorden->anyGroupScoredQuestions()
        ],
        [
            'attribute' => 'trail_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $timeTrailCheck->anyGroupScoredTimeTrail()
        ],
        [
            'attribute' => 'hint_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
            'visible' => $openNoodEnvelop->anyGroupScoredOpenedHints()
        ],
        [
            'attribute' => 'total_score',
            'visible'=> TRUE,
            'filter' => FALSE,
            'contentOptions' => ['class' => 'kv-align-center'],
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
