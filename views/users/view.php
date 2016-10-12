<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\EventNames;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('app', 'Overview') . ' '. $model->username;

?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $attributes = [
        [
            'group' => true,
            'label' => $model->username,
            'rowOptions' => ['class' => 'info']
        ],
        [
            'columns' => [
                [
                    'attribute' => 'username',
                    'label' => 'Book #',
                    'displayOnly' => true,
                    'valueColOptions' => ['style' => 'width:30%']
                ],
                [
                    'attribute' => 'email',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                    'displayOnly' => true
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'voornaam',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'achternaam',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'organisatie',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'birthdate',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'last_login_time',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'selected_event_ID',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                    'value' => EventNames::getEventName($model->selected_event_ID),
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'authKey',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'accessToken',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
    ];

    // View file rendering the widget
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
        'mode' => 'view',
//        'bordered' => $bordered,
//        'striped' => $striped,
//        'condensed' => $condensed,
//        'responsive' => $responsive,
//        'hover' => $hover,
//        'hAlign'=>$hAlign,
//        'vAlign'=>$vAlign,
//        'fadeDelay'=>$fadeDelay,
        'deleteOptions' => [ // your ajax delete parameters
            'params' => ['id' => 1000, 'kvdelete' => true],
        ],
        'container' => ['id' => 'kv-demo'],
        'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
    ]); 
    
    
        Modal::begin(
        [
            'toggleButton' => [
                'label' => Yii::t('app', 'Change settings user'),
                'class' => 'btn btn-success pull-right'
            ],
            'closeButton' => [
                'label' => 'Close',
                'class' => 'btn btn-danger btn-sm pull-right',
            ],
            'size' => Modal::SIZE_LARGE,
        //'options' => ['class'=>'slide'],
        ]
    );
    echo $this->render('_form', ['model' => $model]);
    Modal::end();
    ?>
</div>
