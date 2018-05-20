<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use app\components\CustomAlertBlock;
use prawee\widgets\ButtonAjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BonuspuntenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview bonuspoints');
?>
<div class="bonuspunten-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    Modal::begin(['id' => 'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    echo CustomAlertBlock::widget([
        'type' => CustomAlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 20000,
    ]);

    $gridColumns = [
        [
            'attribute' => 'group_name',
            'value' => 'group.group_name'
        ],
        'date',
        [
            'attribute' => 'post_name',
            'value' => 'post.post_name',
        ],
        'omschrijving',
        'score',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{update}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return ButtonAjax::widget([
                        'name' => '<span class="glyphicon glyphicon-pencil"></span>',
                        'route' => ['bonuspunten/update', 'bonuspunten_ID' => $model->bouspunten_ID],
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
            'visibleButtons' => [
                'edit' => function ($model, $key, $index) {
                    if (Yii::$app->user->can('organisatie')) {
                        return true;
                    }
                    return false;
                },
            ]
        ],
    ];

    $bordered = false;
    $striped = true;
    $condensed = true;
    $responsive = false;
    $hover = true;
    $pageSummary = false;
    $heading = false;
    $exportConfig = false;

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
