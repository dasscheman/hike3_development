<?php

use app\models\Users;
use kartik\builder\Form;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\widgets\FileInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');
?>


<div class="organisatie-overview">

    <?php

//    d(Yii::$app->basePath . ''. Yii::$app->params['event_images_path'] . $eventModel->image);
//
//    d(Yii::$app->params['event_images_path'] . $eventModel->image);
//
//echo Html::img(Yii::$app->params['event_images_url'] . $eventModel->image);
//
//echo Html::img('uploads/event_images/aangifte.jpg');

//    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
//    $form->field($eventModel, 'image_temp')->widget(FileInput::classname(), [
//
//        'model' => $eventModel,
//        'options' => ['multiple' => false, 'accept' => 'image/*', 'maxFileSize' => 10280,],
//        'pluginOptions' => [
//            'allowedFileExtensions'=>['jpg','gif','png'],
////            'uploadUrl' => Url::to(['/organisatie/overview']),
////            'previewFileType' => 'image',
////            'showCaption' => false,
////            'showRemove' => true,
////            'showUpload' => true,
////            'browseClass' => 'btn btn-primary btn-block',
////            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
////            'browseLabel' => Yii::t('app', 'Select Photo')
//        ]
//    ]);
//    ActiveForm::end();

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
                    'label' => 'organisatie',
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
                    'value' => $eventModel->getStatusText(),
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
            'attribute'=>'image',
            'value'=>Yii::$app->params['event_images_url'] . $eventModel->image,
            'format' => ['image',['width'=>'100','height'=>'100']],
        ],
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
        'container' => ['id' => 'hike-overview'],
        'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
    ]);


    $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data'] // important
    ]);
    // your fileinput widget for single file upload
    echo $form->field($eventModel, 'image_temp')->widget(FileInput::classname(), [
        'options'=>['accept'=>'image/*'],
        'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'jpeg', 'gif','png'],
////            'uploadUrl' => Url::to(['/organisatie/overview']),
//                'previewFileType' => 'image',
//                'showCaption' => false,
//                'showRemove' => true,
//                'showUpload' => true,
//                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => Yii::t('app', 'Select Photo')
]
    ]);
    ActiveForm::end();

    Modal::begin(
        [
            'id' =>'modalMessage',
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
    echo $this->render('/event-names/_form', ['model' => $eventModel, 'action' => 'change_status']);
    Modal::end();

    Modal::begin(
        [
            'id' =>'modalMessage',
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
    echo $this->render('/event-names/_form', ['model' => $eventModel, 'action' => 'edit_settings']);
    Modal::end();

    echo ButtonAjax::widget([
        'name'=>'Create',
        'route'=>['/groups/create'],
        'modalId'=>'#group-create-modal',
        'modalContent'=>'#group-create-modal',
        'options'=>[
            'class'=>'btn btn-success',
            'title' => Yii::t('app', 'Create group'),
        ]
    ]);

    Modal::begin(['id'=>'group-create-modal']);
    echo '<div id="group-create-modal"></div>';
    Modal::end();

    
    
     
    ?>



</div>
<div class="groups-overview">
    <td style="vertical-align:top">
        <?php
        echo "Groepen die ingeschreven staan"; ?>
        <div class="row">
            <?php
            echo ListView::widget([
                'summary' => FALSE,
                'pager' => FALSE,
                'dataProvider' => $groupsData,
                'itemView' => '/groups/_list',
                'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
            ]);
            ?>
        </div>
    </td>
</div>
<?= Yii::$app->language ?>