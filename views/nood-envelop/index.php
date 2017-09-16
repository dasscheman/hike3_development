<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\DeelnemersEvent;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Search hints');
?>
<div class="nood-envelop-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php

    Modal::begin(['id'=>'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    $gridColumns = [
        [
        'attribute' => 'nood_envelop_name',
        ],
        [
        'attribute' => 'route_name',
        'value' => 'route.route_name'
        ],
        [
        'attribute' => 'score',
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{open}',
            'buttons' => [
                'open' => function ($url, $model) {
                    return ButtonAjax::widget([
                        'name' => Yii::t('app', 'Open Hint'),
                        'route' => [
                            '/open-nood-envelop/open',
                            'nood_envelop_ID'=>$model->nood_envelop_ID,
                            'group_ID' => DeelnemersEvent::getGroupOfPlayer()
                        ],
                        'modalId'=>'#main-modal',
                        'modalContent'=>'#main-content-modal',
                        'options' => [
                            'class' => 'btn btn-xs btn-success',
                            'title' => Yii::t('app', 'Open Hint'),
                            'disabled' => !Yii::$app->user->identity->isActionAllowed('open-nood-envelop', 'open', ['nood_envelop_ID'=>$model->nood_envelop_ID, 'group_ID' => DeelnemersEvent::getGroupOfPlayer()]),
                        ]
                    ]);


//                    return Html::a(
//                        '<span class="fa fa-search"></span>Open',
//                        [
//                            '/open-nood-envelop/open',
//                            'nood_envelop_ID'=>$model->nood_envelop_ID,
//                            'group_ID' => DeelnemersEvent::getGroupOfPlayer()
//                        ],
//                        [
//                            'id' => 'open-hint-'. $model->nood_envelop_ID,
//                            'title' => Yii::t('app', 'Open hint'),
//                            'class'=>'btn btn-primary btn-xs',
//                        ]
//                    );
                },
            ],
            'visibleButtons' => [
                'open' => function ($model, $key, $index) {
                    return Yii::$app->user->identity->isActionAllowed(
                        'open-nood-envelop',
                        'open',
                        ['nood_envelop_ID'=>$model->nood_envelop_ID, 'group_ID' => DeelnemersEvent::getGroupOfPlayer()]);
                }
            ]
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
        'columns' => $gridColumns,
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
