<?php


use app\models\DeelnemersEvent;
use kartik\grid\GridView;
use kartik\widgets\AlertBlock;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\EventNames;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('app', 'Select hike');

?>
<div class="select-hike">

    <h1><?= Html::encode($this->title) ?></h1>

<?php
    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => FALSE,

    ]);
    Modal::begin(
    [
        'id' => 'create-hike-modal',
        'closeButton' => [
            'label' => Yii::t('app', 'Close'),
            'class' => 'btn btn-danger btn-sm pull-right',
        ],
        'size' => Modal::SIZE_LARGE,
    ]);
    Pjax::begin([
        'id' => 'event-names-create-form',
        'enablePushState' => FALSE,
    ]);
    echo $this->render('/event-names/create', [
        'model' => new EventNames([
            'start_date' => date('d-m-Y'),
            'end_date' => date('d-m-Y')] ),
        'action' => 'create'
    ]);
    Pjax::end();
    Modal::end();
    $dataProvider = new ActiveDataProvider([
        'query' => $modelEvents,
    ]);

    $gridColumns = [
        'event_name',
        'start_date',
        'status' => [
            'attribute' => 'status',
            'value' => function($model){
                return $model->getStatusText();
            },
        ],
        [
            'header' => 'Rol',
            'value' => function($key){
                return DeelnemersEvent::getRolOfCurrentPlayer($key);
            },
        ],
        'organisatie',
        'website',
        [
            'class' => 'yii\grid\ActionColumn',
            'header'=>'Actions',
            'template' => '{select}',
            'buttons' => [
                'select' => function ($url, $model) {
                    return Html::a(
                        '<span class="fa fa-search"></span>Select',
                        [
                            'event-names/select-hike',
                            'event_ID' => $model->event_ID],
                        [
                            'id' => 'select-hike-'. $model->event_ID,
                            'title' => Yii::t('app', 'Select hike'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
            ],
            'visibleButtons' => [
                'select' => function ($model, $key, $index) {
                    return $model->event_ID == Yii::$app->user->identity->selected_event_ID ? FALSE : TRUE;
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
    $exportConfig = TRUE;
    $responsiveWrap = FALSE;

    echo GridView::widget([
        'id' => 'kv-grid-hike_select',
        'dataProvider'=>$dataProvider,
        'columns'=>$gridColumns,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'pjax'=>FALSE, // FALSE, anders wordt de header niet opnieuw geladen.
        // set your toolbar
        'toolbar'=> [
            ['content'=>
                ButtonAjax::widget([
                    'name'=>Yii::t('app', 'Create new hike'),
                    'route'=>['event-names/create'],
                    'modalId'=>'#create-hike-modal',
                    'modalContent'=>'#create-hike-content-modal',
                    'options'=>[
                        'class'=>'btn btn-success',
                        'title'=>Yii::t('app', 'Create new hike'),
                    ]
                ]),
            ],
            //'{export}',
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
    ?>

</div>
