<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use prawee\widgets\ButtonAjax;
use app\models\EventNames;
use app\models\DeelnemersEvent;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('app', 'Select hike');

?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

<?php

    $dataProvider = new ActiveDataProvider([
        'query' => $modelEvents,
    ]);

    $gridColumns = [
        'event_name',
        'start_date',
        'end_date',
        'status' => [
            'attribute' => 'status',
            'value' => function($model){
                return $model->getStatusText();
            },
        ],
        'active_day',
        [
            'header' => 'Rol',
            'value' => function($key){
                return DeelnemersEvent::getRolOfCurrentPlayer($key);
            },
        ],
        'organisatie',
        'website',
        [
            'header' => 'Aangemaakt',
            'value' => function($key){
                return EventNames::findOne($key)->getCreateUser()->one()->username;
            },
        ],
        [
            // EXAMPLE
            'header' => 'Laatst Bijgewerkt',
            'value' => function($key){
                return EventNames::findOne($key)->getUpdateUser()->one()->username;
            },

        ],
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
                            'id' => $model->event_ID],
                        [
                            'title' => Yii::t('app', 'Select hike'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                },
            ],
            'visibleButtons' => [
                'select' => function ($model, $key, $index) {
                    return $model->event_ID == Yii::$app->user->identity->selected ? FALSE : TRUE;
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
                    'name'=>'Create',
                    'route'=>['event-name/create'],
                    'modalId'=>'#main-modal',
                    'modalContent'=>'#main-content-modal',
                    'options'=>[
                        'class'=>'btn btn-success',
                        'title'=>'Button for create application',
                    ]
                ]),
            ],
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
