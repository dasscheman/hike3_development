<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$bordered = false;
$striped = true;
$condensed = true;
$responsive = false;
$hover = true;
$pageSummary = false;
$heading = false;
$exportConfig = true;
$resizableColumns = false;
$responsiveWrap = false;

$this->title = Yii::t('app', 'Tracks');
?>
<div class="track-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        De hike-app heeft nu de mogelijkheid om de gps locatie op te halen uit je browser. <br>
        Tijdens de hike kan de organisatie dan zien waar de en hoe de groepjes lopen.<br>
        Verder wordt voor de organisatie ook de locatie van alle organisatieleden op een kaart getoond.<br>
        Als de hike is afgelopen en status 'ended' heeft, dan kunnen alle groepjes ook elkaars route bekijken.<br>
        <b>Uitleg:</b>
        <li>Je locatie wordt alleen opgevraaagd als ondestaaande tracking knop op 'aan' staat.</li>
        <li>Verder vraagt je browser of je je lokatie wilt delen met hike-app.nl.</li>
        <li>Je locatie wordt alleen verzonden als de status van de hike 'gestart' is. Tijdens de introductie wordt er geen lokaties opgehaald.</li>
        <li>Hieronder kun je ten alle tijde je lokatie data wissen. Let op! het is ook echt weg en kan niet meer terug gehaald worden.</li>

    </p>
    <p>
        <?php
        echo '<label class="control-label">Tracking</label>';
        echo SwitchInput::widget([
            'name' => 'tracking',
            'value' => Yii::$app->user->identity->allow_track,
            'pluginOptions' => [
                'size' => 'large',
                'onColor' => 'success',
                'offColor' => 'danger',
            ],
            'pluginEvents' => [
                'switchChange.bootstrapSwitch' => "function() { switchAllowTracking()}"
            ]
        ]);
        
        ?>
    </p>
    
    <?php
    $gridColumns = [
        [
            'attribute' => 'event_name',
            'value' => 'event.event_name'
        ],
        [
            'attribute' => 'voornaam',
            'value' => 'user.voornaam'
        ],
        [
            'attribute' => 'group_name',
            'value' => 'group.group_name'
        ],
//        'latitude',
            //'longitude',
            //'accuracy',
            //'timestamp:datetime',
            //'create_time',
            //'create_user_ID',
            //'update_time',
            //'update_user_ID',
//        [
//            'attribute' => 'group_name',
//            'value' => 'group.group_name'
//        ],
//        'date',
//        [
//            'attribute' => 'post_name',
//            'value' => 'post.post_name',
//        ],
//        'omschrijving',
//        'score',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>',
                        ['delete', 'event_id' => $model->event_ID],
                        [
                            'class' => '',
                            'data' => [
                                'confirm' => 'Are you absolutely sure ? You will lose all gps information for this event.',
                                'method' => 'post',
                            ],
                        ]
                    );
                },
            ],
        ]
    ];
                
    echo GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
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
