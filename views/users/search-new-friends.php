<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Search for new friends');
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    yii\widgets\Pjax::begin(['id' => 'searchfriends', 'enablePushState' => false]);

    echo $this->render('_search', ['model' => $searchModel]);

    $bordered = FALSE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = FALSE;
    $exportConfig = TRUE;;
    $resizableColumns = FALSE;
    $responsiveWrap = FALSE;

    $gridColumns = [
        'voornaam',
        'achternaam',
        'organisatie',
        // 'last_login_time',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{connect}',
            'buttons' => [
                'connect' => function ($url, $model) {
                    return Html::a(
                        Yii::t('app', 'Invite'),
                        ['friend-list/connect', 'user_id'=>$model->id],
                        [
                            'title' => Yii::t('app', 'Invite'),
                            'class' =>'btn btn-primary btn-xs',
                        ]
                    );
                }
            ],
        ]
    ];



    echo GridView::widget([
        'id' => 'kv-grid-hike_select',
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns'=>$gridColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'resizableColumns' => $resizableColumns,
        'pjax'=>TRUE, // FALSE, anders wordt de header niet opnieuw geladen.
        // set your toolbar
        'toolbar'=> FALSE,
        // parameters from the demo form
        'bordered'=>$bordered,
        'striped'=>$striped,
        'condensed'=>$condensed,
        'responsive'=>$responsive,
        'responsiveWrap' => $responsiveWrap,
        'hover'=>$hover,
        'showPageSummary'=>$pageSummary,
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=>$heading,
        ],
        'persistResize'=>false,
        //'exportConfig'=>$exportConfig,
    ]);

    yii\widgets\Pjax::end(); ?>
</div>
