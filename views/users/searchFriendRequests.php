<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'View friend requests');
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php

    $gridColumns = [
        'username',
        'voornaam',
        'achternaam',
        'organisatie',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{accept} {decline}',
            'buttons' => [
                'accept' => function ($url, $model) {
                    return Html::a(
                        Yii::t('app', 'Accept'),
                        ['friend-list/accept', 'user_id'=>$model->id],
                        [
                            'title' => Yii::t('app', 'Accept'),
                            'class' =>'btn btn-success btn-xs',
                        ]
                    );
                },
                'decline' => function ($url, $model) {
                    return Html::a(
                        Yii::t('app', 'Decline'),
                        ['friend-list/decline', 'user_id'=>$model->id],
                        [
                            'title' => Yii::t('app', 'Decline'),
                            'class' =>'btn btn-danger btn-xs',
                        ]
                    );
                }
            ],
        ]
    ];

    $bordered = FALSE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = TRUE;
    $pageSummary = FALSE;
    $heading = FALSE;
    $exportConfig = TRUE;
    $responsiveWrap = FALSE;

    yii\widgets\Pjax::begin(['id' => 'searchfriends', 'enablePushState' => false]);
    echo GridView::widget([
        'id' => 'kv-grid-hike_select',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns'=>$gridColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax'=>TRUE, // FALSE, anders wordt de header niet opnieuw geladen.
        // set your toolbar
        'toolbar'=> [
            '{export}',
            '{toggleData}',
        ],
        // set export properties
        'export'=>[
            'fontAwesome'=>true
        ],
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
