<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\grid\GridView;
use app\models\EventNames;

/* @var $this yii\web\View */

?>
<div class="organisatie-overview">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    
    
    //var_dump(EventNames::getStatusOptions()); exit;
    
    $attributes = [
        [
            'columns' => [
                [
                    'attribute'=>'event_name', 
                    'displayOnly'=>true,
                    'valueColOptions'=>['style'=>'width:30%'],
                    'labelColOptions'=>['style'=>'width:10%']
                ],
                [
                    'attribute'=>'start_date',
                    'labelColOptions'=>['style'=>'width:10%'],
                    'displayOnly'=>true
                ],
                [
                    'attribute'=>'end_date',        
                    'labelColOptions'=>['style'=>'width:10%'],
                    'displayOnly'=>true
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute'=>'status',
                    'labelColOptions' => ['style'=>'width:10%'],
                    'valueColOptions' => ['style'=>'width:10%'],
                    'value' => $eventModel->getStatusText(),
                    'format' => 'raw',
                    'type' => DetailView::INPUT_SELECT2, 
                    'widgetOptions' => [
                        'data' => EventNames::getStatusOptions(),
                        'options' => ['placeholder' => Yii::t('app', 'Select ...')],
                        'pluginOptions' => ['allowClear'=>true, 'width'=>'100%'],
                    ],
                    
                ],
                [
                    'attribute'=>'active_day', 
                    'type'=>DetailView::INPUT_DATE,
                    'labelColOptions'=>['style'=>'width:10%'],
                    'valueColOptions'=>['style'=>'width:10%'],                     
                    //'value' => $eventModel->getStatusText(),
                    //'format' => 'raw',
                    //'type' => DetailView::INPUT_SELECT2, 
                    'widgetOptions' => [
//                        'minDate' => $eventModel->start_date,
//                        'maxDate' => $eventModel->end_date,
//                        'data' => EventNames::getStatusOptions(),
//                        'options' => ['placeholder' => Yii::t('app', 'Select ...')],
                        'pluginOptions' => ['minDate' => $eventModel->start_date,],
                    ],
                ],
                [
                    'attribute'=>'max_time', 
                    'type'=>DetailView::INPUT_TIME,
                    'labelColOptions'=>['style'=>'width:10%'],
                    'valueColOptions'=>['style'=>'width:10%'], 
                ],
            ],
        ],
    ];
    
echo DetailView::widget([
    'model'=>$eventModel,
    'condensed'=>true,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>'Hike ' . $eventModel->event_name,
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes'=>$attributes
//    
////        'attribute' =>[
////            'attribute' => [
//        'columns' => [
//            [
//                'attribute'=>'event_name',
//                'displayOnly'=> TRUE,
//            ], 
//            [
//                'attribute'=>'start_date',
//                'displayOnly'=> TRUE,
//            ],
//            [
//                'attribute'=>'end_date',
//                'displayOnly'=> TRUE,
//            ],
//        ],
////            ]
////        ],
//        'status',
//        ['attribute'=>'active_day', 'type'=>DetailView::INPUT_DATE],
////        'attribute' =>[
////            'attribute' => [
////                'columns' => [
////                    [
////                        'attribute'=>'status',
////                    ], 
////                    [
////                        'attribute'=>'active_day',
////                    ],
////                    [
////                        'attribute'=>'max_time',
////                    ],
////                ]
////            ]
////        ],
////        ]
//    ]
]);
    ?>
    <?php /*= DetailView::widget([
        'model' => $eventModel,
        'attributes' => [
            'event_ID',
            'status',
            'active_day',
            'start_date',
            'end_date',
        ],
    ]);*/ ?>

    <?= GridView::widget([
        'dataProvider' => $groupsData,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'post_ID',
            'post_name',
            'event_ID',
            'date',
            'post_volgorde',
            // 'score',
            // 'create_time',
            // 'create_user_ID',
            // 'update_time',
            // 'update_user_ID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); 
      
       ?>
     

</div>
