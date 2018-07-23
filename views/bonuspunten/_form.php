<?php

use app\models\EventNames;
use app\models\Posten;
use app\models\Groups;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Bonuspunten */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bonuspunten-form">

    <?php
    Pjax::begin([
        'id' => 'bonuspunten-form-' . $model->bouspunten_ID,
        'enablePushState' => false,
    ]);

    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]);

    $form = ActiveForm::begin([
        'options'=>[
          'id' => 'bonuspunten-form'
//            'data-pjax'=>TRUE,
        ],
    ]); ?>
    <?php
    if ($model->isNewRecord) {
        echo $form->field($model, 'group_ID', [
            'options' => [
                'id' => 'bonuspunten-group-field-create',
            ],
        ])->dropDownList(
            Groups::getGroupOptionsForEvent(),
            [
                'prompt'=>'Select...',
                'id' => 'bonuspunten-group-dropdown-create'
            ]
        );
    }
    echo $form->field($model, 'date')->dropDownList(
        EventNames::getDatesAvailable(),
        [
            'prompt'=>'Select...',
            'id' => 'date-' . $model->bouspunten_ID,
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        ]
    );

    // EXAMPLE Dependent Dropdown
    echo $form->field($model, 'post_ID')->widget(DepDrop::classname(), [
        'options' => ['id' => 'post_ID-' . $model->bouspunten_ID],
        'pluginOptions' => [
            'value' => [$model->post_ID => $model->post_name],
            'depends' => ['date-' . $model->bouspunten_ID],
            'placeholder' => 'Select...',
            'initialize' => true,
            'url' => Url::to(['/posten/lists-posts', 'post_id' => $model->post_ID])
        ]
    ]);

    echo $form->field($model, 'omschrijving')->textInput();
    echo $form->field($model, 'score')->textInput(['type' =>  'number']);
    ?>
    <div class="form-group">
        <?php
        if (!$model->isNewRecord) {
            echo Html::a(
                Yii::t('app', 'Save'),
                [
                    '/bonuspunten/update',
                    'bonuspunten_ID' => $model->bouspunten_ID
                ],
                [
                    'class' => 'btn btn-xs btn-success',
                    'data-method'=>'post',
                    'data-pjax' => 'bonuspunten-form-' . $model->bouspunten_ID
                ]
            );
            echo Html::a(
                Yii::t('app', 'Delete'),
                [
                    '/bonuspunten/delete',
                    'bonuspunten_ID' => $model->bouspunten_ID],
                [
                    'class' => 'btn btn-xs btn-danger',
                    'data-method'=>'post',
                    'data-pjax' => 'bonuspunten-form-' . $model->bouspunten_ID
                ]
            );
        }
        if ($model->isNewRecord) {
            echo Html::a(
                Yii::t('app', 'Save'),
                [
                    '/bonuspunten/create',
                ],
                [
                    'class' => 'btn btn-xs btn-primary',
                    'id' => 'save-create-bonuspunten',
                    'data-method'=>'post',
                    'data-pjax' => 'bonuspunten-form-' . $model->bouspunten_ID
                ]
            );
        }
        ?>
    </div>
    <?php
    ActiveForm::end();
    Pjax::end(); ?>

</div>
