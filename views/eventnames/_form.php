<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\TblEventNames */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-event-names-form">
    <?php 
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL]);
    echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'columns'=>2,
        'attributes'=>[       // 2 column layout
            'event_name'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Hike name')]],
            'organisatie'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>Yii::t('app', 'Organisation')]]
        ]
    ]);
    echo Form::widget([       // 1 column layout
        'model'=>$model,
        'form'=>$form,
        'columns'=>1,
        'attributes'=>[
            'image'=>[
                'type'=>Form::INPUT_FILE, 
            ]
        ]
    ]);
    echo Form::widget([     // nesting attributes together (without labels for children)
        'model'=>$model,
        'form'=>$form,
        'columns'=>2,
        'attributes'=>[
            'date_range' => [
                'label' => Yii::t('app', 'Date Range'),
                'attributes'=>[
                    'start_date' => [
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass'=>'\kartik\widgets\DatePicker', 
                        'options'=>[
                            'options'=>[
                                'options'=>['placeholder'=>'Date from...']
                            ],
                        ],
                        'hint'=>Yii::t('app', 'Enter start date')
                    ],
                    'end_date'=>[
                        'type'=>Form::INPUT_WIDGET, 
                        'widgetClass'=>'\kartik\widgets\DatePicker', 
                        'options'=>[
                            'options'=>[
                                'options'=>['placeholder'=>'Date to...', 'class'=>'col-md-9']
                            ]
                        ],
                        'hint'=>Yii::t('app', 'Enter end date')
                    ]
                ]
            ],
        ]
    ]);
    
    echo Form::widget([       // 3 column layout
        'model'=>$model,
        'form'=>$form,
        'columns'=>3,
        'attributes'=>[
            'website'=>[
                'type'=>Form::INPUT_TEXT, 
                'options'=>['placeholder'=>Yii::t('app', 'Website organisatie')]
            ],      
            'status'=>[
                'type'=>Form::INPUT_DROPDOWN_LIST,
                'items' => \app\models\EventNames::getStatusOptions(),
                'options'=>['placeholder'=>Yii::t('app', 'status'),
                    'disabled' => $model->isNewRecord, 
                ],
            ],   
            'actions'=>[
                'type'=>Form::INPUT_RAW, 
                'value'=>'<div style="text-align: right; margin-top: 20px">' . 
                    Html::resetButton(Yii::t('app', 'Reset'), ['class'=>'btn btn-default']) . ' ' .
                    Html::submitButton(Yii::t('app', 'Save'), ['type'=>'button', 'class'=>'btn btn-primary']) . 
                    '</div>'
            ],
        ]
    ]);
    ActiveForm::end(); 
    
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['multiple' => false, 'accept' => 'image/*', 'maxFileSize'=> 10280,],
        'pluginOptions' => [
            'previewFileType' => 'image',
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-primary btn-block',
            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
            'browseLabel' =>  Yii::t('app', 'Select Photo')
        ]
    ]);
    ActiveForm::end();?>
</div>
