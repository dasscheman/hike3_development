<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Posten;
use app\models\Groups;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;
use app\components\CustomAlertBlock;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posten');
?>

<div class="posten-index">

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

    echo CustomAlertBlock::widget([
        'type' => CustomAlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 20000,
    ]);
    $count = 0;
    $gridColumns = [
        [
            'attribute' => 'post_name',
            'format' => 'raw',
            // here comes the problem - instead of parent_region I need to have parent
            'value' => function ($model, $key, $index, $column) {
                return ButtonAjax::widget([
                        'name' => $model->post_name,
                        'route' => ['posten/update', 'post_ID' => $key],
                        'modalId' => '#main-modal',
                        'modalContent' => '#main-content-modal',
                        'options' => [
                            'class' => 'btn btn-xs btn-primary',
                            'title' => 'Edit',
                            'disabled' => !Yii::$app->user->can('organisatie'),
                        ]
                ]);
            },
        ],
        [
            'header' => Yii::t('app', 'View check in/out'),
            'class' => 'kartik\grid\ExpandRowColumn',
            'width' => '50px',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $db = $model::getDb();
                $groups = $db->cache(function ($db) {
                    return Groups::find()
                                ->where('event_ID =:event_id')
                                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
                                ->all();
                });
                return Yii::$app->controller->renderPartial('/post-passage/view-groups', ['post_id' => $key, 'groups' => $groups]);
            },
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'expandOneOnly' => true,
            'expandTitle' => Yii::t('app', 'Open view groups'),
            'collapseTitle' => Yii::t('app', 'Close view groups'),
        ],
        'score',
        [
            'header' => Yii::t('app', '#groups passed'),
            'value' => function ($model, $key, $index, $column) {
                $db = $model::getDb();
                return $db->cache(function ($db) use ($key) {
                    return Posten::findOne($key)->getPostPassagesCount();
                });
            },
        ],
        'post_volgorde',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{up} {down}',
            'buttons' => [
                'up' => function ($url, $model) {
                    return Html::a(
                            '<span class="glyphicon glyphicon-chevron-up"></span>',
                        [
                            'posten/move-up-down',
                            'post_ID' => $model->post_ID,
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
                            'posten/move-up-down',
                            'post_ID' => $model->post_ID,
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
                        Posten::lowererOrderNumberExists($model->date, $model->post_volgorde)) {
                        return true;
                    }
                    return false;
                },
                'down' => function ($model, $key, $index) {
                    if (Yii::$app->user->can('organisatie') &&
                        Posten::higherOrderNumberExists($model->post_ID)) {
                        return true;
                    }
                    return false;
                }
            ]
        ],
    ];

    while (strtotime($startDate) <= strtotime($endDate)) {
        $dataArray[$count] = array(
            'label' => $startDate,
            'active' => $startDate === Yii::$app->getRequest()->getCookies()->getValue('posten_day_tab') ? true : false,
            'content' => GridView::widget([
                'id' => 'kv-grid-' . $startDate, //'kv-grid-demo',
                'dataProvider' => $searchModel->searchPostenInEvent(['PostenSearch' => ['date' => $startDate]]),
                'columns' => $gridColumns,
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'responsiveWrap' => $responsiveWrap,
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                'pjax' => true, // pjax is set to always true for this demo
                // set your toolbar
                'toolbar' => [
                    ['content' =>
                        ButtonAjax::widget([
                            'name' => Yii::t('app', 'Add station'),
                            'route' => ['posten/create', 'date' => $startDate],
                            'modalId' => '#main-modal',
                            'modalContent' => '#main-content-modal',
                            'options' => [
                                'class' => 'btn btn-success',
                                'title' => Yii::t('app', 'Create new station'),
                                'disabled' => !Yii::$app->user->can('organisatieOpstart') && !Yii::$app->user->can('organisatieIntroductie'),
                            ]
                        ]),
                    ],
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
            ])
        );
        $startDate = date('Y-m-d', strtotime($startDate . ' + 1 days'));
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
