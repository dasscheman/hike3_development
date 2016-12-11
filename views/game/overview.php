<?php

use app\models\Users;
use kartik\builder\Form;
use kartik\detail\DetailView;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');
?>


<div class="organisatie-overview">

    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $form->field($eventModel, 'image')->widget(kartik\widgets\FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*', 'maxFileSize' => 10280,],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showCaption' => false,
            'showRemove' => true,
            'showUpload' => true,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' => Yii::t('app', 'Select Photo')
        ]
    ]);
    ActiveForm::end();

    $attributes = [
        [
            'group' => true,
            'label' => $eventModel->event_name,
            'rowOptions' => ['class' => 'info']
        ],
        [
            'columns' => [
                [
                    'attribute' => 'organisatie',
                    'label' => 'Book #',
                    'displayOnly' => true,
                    'valueColOptions' => ['style' => 'width:30%']
                ],
                [
                    'attribute' => 'website',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                    'displayOnly' => true
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'start_date',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'end_date',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'status',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'active_day',
                    'format' => 'raw',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
        [
            'columns' => [
                [
                    'attribute' => 'max_time',
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
                [
                    'attribute' => 'create_user_ID',
                    'format' => 'raw',
                    'value' => Users::getUserName($eventModel->create_user_ID),
                    'valueColOptions' => ['style' => 'width:30%'],
                ],
            ],
        ],
        [
            'attribute' => 'image',
            'format' => 'raw',
            'value' => Form::widget([       // 1 column layout
                'model' => $eventModel,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    'image' => [
                        'type' => Form::INPUT_FILE,
                    ]
                ]
            ]),
            'options' => ['rows' => 4]
        ]
    ];

    // View file rendering the widget
    echo DetailView::widget([
        'model' => $eventModel,
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
                'label' => Yii::t('app', 'Change settings hike'),
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
    echo $this->render('/event-names/_form', ['model' => $eventModel]);
    Modal::end();
    ?>

    <?php
    Modal::begin(
        [
            'toggleButton' => [
                'label' => Yii::t('app', 'Change status hike'),
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
    echo $this->render('/event-names/_form', ['model' => $eventModel]);
    Modal::end();
    
    echo ButtonAjax::widget([
        'name'=>'Create',
        'route'=>['groups/create'],
        'modalId'=>'#main-modal',
        'modalContent'=>'#group-content-modal',
        'options'=>[
            'class'=>'btn btn-success',
            'title'=>'Button for create application',
        ]
    ]);

    Modal::begin(['id'=>'main-modal']);
    echo '<div id="group-content-modal"></div>';
    Modal::end();

    
    
     
    ?>



</div>

<div class="route-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    Modal::begin(['id'=>'main-modal']);
    echo '<div id="main-content-modal"></div>';
    Modal::end();

    $count=0;
    $gridColumns = [
        'route_name',
        'route_volgorde',
        [
            'header' => Yii::t('app', '#Questions'),
            'value' => function($key){
                return Route::findOne($key)->getOpenVragenCount();
            },
        ],
        [
            'header' => Yii::t('app', 'View Questions'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-vragen-antwoorden/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open view questions'),
            'collapseTitle' => Yii::t('app', 'Close view questions'),
        ],
        [
            'header' => Yii::t('app', '#Hints'),
            'value' => function($key){
                return Route::findOne($key)->getNoodEnvelopCount();
            },
        ],
        [
            'header' => Yii::t('app', 'View hints'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/open-nood-envelop/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
        ],
        [
            'header' => Yii::t('app', '#Silent posts'),
            'value' => function($key){
                return Route::findOne($key)->getQrCount();
            },
        ],
        [
            'header' => Yii::t('app', 'View silent posts'),
            'class'=>'kartik\grid\ExpandRowColumn',
            'width'=>'50px',
            'value'=> function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail'=>function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('/qr-check/view', ['model'=>$model]);
            },
            'headerOptions'=>['class'=>'kartik-sheet-style'],
            'expandOneOnly'=>true,
            'expandTitle' => Yii::t('app', 'Open view hints'),
            'collapseTitle' => Yii::t('app', 'Close view hints'),
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

    $dataArray[$count]=array(
        'label' => Yii::t('app', 'Introduction'),
        'content' => GridView::widget([
            'id' => 'kv-grid-0000-00-00',
            'dataProvider'=>$searchModel->search(['RouteSearch' => ['day_date' => '0000-00-00']]),
            'columns'=>$gridColumns,
            'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            'pjax'=>true, // pjax is set to always true for this demo
            // set your toolbar
            'toolbar'=> [
                ['content'=>
                    ButtonAjax::widget([
                        'name'=>'Create',
                        'route'=>['route/create', 'date' => '0000-00-00'],
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
        ])
    );
    $count++;

    while(strtotime($startDate) <= strtotime($endDate)) {
        $dataArray[$count]=array(
		    'label' =>$startDate,
		    'content' => GridView::widget([
                'id' => 'kv-grid-' . $startDate, //'kv-grid-demo',
                'dataProvider'=>$searchModel->search(['RouteSearch' => ['day_date' => $startDate]]),
                'columns'=>$gridColumns,
                'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                'pjax'=>true, // pjax is set to always true for this demo
                // set your toolbar
                'toolbar'=> [
                    ['content'=>
                        ButtonAjax::widget([
                            'name'=>'Create',
                            'route'=>['route/create', 'date' => $startDate],
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
            ])
        );
		$startDate = date('Y-m-d', strtotime($startDate. ' + 1 days'));
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
