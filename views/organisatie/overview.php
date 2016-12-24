<?php

use app\models\Users;
use kartik\builder\Form;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Hike overzicht');
?>


<div class="organisatie-overview">

    <?php
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $form->field($eventModel, 'image')->widget(kartik\widgets\FileInput::classname(), [

        'model' => $eventModel,
        'options' => ['multiple' => false, 'accept' => 'image/*', 'maxFileSize' => 10280,],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['/event-names/upload']),
//            'previewFileType' => 'image',
//            'showCaption' => false,
//            'showRemove' => true,
//            'showUpload' => true,
//            'browseClass' => 'btn btn-primary btn-block',
//            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
//            'browseLabel' => Yii::t('app', 'Select Photo')
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
            'attribute' => 'image',
            'format' => 'raw',
            'value' => Form::widget([       // 1 column layout
                'model' => $eventModel,
                'form' =>$form,
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