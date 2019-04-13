<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\DeelnemersEvent;
use app\models\NoodEnvelop;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Hints zoeken');
?>
<div class="nood-envelop-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $bordered = false;
    $striped = true;
    $condensed = true;
    $responsive = false;
    $hover = true;
    $pageSummary = false;
    $heading = false;
    $exportConfig = true;
    $responsiveWrap = false;

    Modal::begin(['id' => 'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();
    $gridColumns = [
        [
            'attribute' => 'nood_envelop_ID',
        ],
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
            'header' => 'Actions',
            'template' => '{open} {up} {down}',
            'buttons' => [
                'open' => function ($url, $model) {
                    return ButtonAjax::widget([
                            'name' => Yii::t('app', 'Open Hint'),
                            'route' => [
                                '/open-nood-envelop/open',
                                'nood_envelop_ID' => $model->nood_envelop_ID,
                                'group_ID' => DeelnemersEvent::getGroupOfPlayer($model->nood_envelop_ID)
                            ],
                            'modalId' => '#main-modal',
                            'modalContent' => '#main-content-modal',
                            'options' => [
                                'class' => 'btn btn-xs btn-success',
                                'title' => Yii::t('app', 'Open Hint'),
                                'disabled' => !Yii::$app->user->can('deelnemerGestartTime') && !Yii::$app->user->can('deelnemerIntroductie'),
                            ]
                    ]);
                },
                'up' => function ($url, $model) {
                    return Html::a(
                            '<span class="glyphicon glyphicon-chevron-up"></span>',
                        [
                            '/nood-envelop/move-up-down',
                            'nood_envelop_id' => $model->nood_envelop_ID,
                            'up_down' => 'up',
                            ],
                        [
                            'title' => Yii::t('app', 'Move up'),
                            'class' => 'btn btn-primary btn-xs',
                            ]
                    );
                },

                'down' => function ($url, $model) {
                    return Html::a(
                            '<span class="glyphicon glyphicon-chevron-down"></span>',
                        [
                            '/nood-envelop/move-up-down',
                            'nood_envelop_id' => $model->nood_envelop_ID,
                            'up_down' => 'down',
                            ],
                        [
                            'title' => Yii::t('app', 'Mode down'),
                            'class' => 'btn btn-primary btn-xs',
                            ]
                    );
                },
            ],
            'visibleButtons' => [
              'up' => function ($model, $key, $index) {
                if (Yii::$app->user->can('organisatie') &&
                    NoodEnvelop::lowererOrderNumberExists($model->nood_envelop_ID)) {
                    return true;
                }
                return false;
              },
              'down' => function ($model, $key, $index) {
                if (Yii::$app->user->can('organisatie') &&
                NoodEnvelop::higherOrderNumberExists($model->nood_envelop_ID)) {
                  return true;
                }
                return false;
              }
            ]
        ],
    ];

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'responsiveWrap' => $responsiveWrap,
        'pjax' => true, // pjax is set to always true for this demo
        'toolbar' => false,
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
        'exportConfig' => $exportConfig,
    ]);
    ?>

</div>
