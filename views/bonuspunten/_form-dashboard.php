<?php //

use app\components\SetupDateTime;
use app\models\EventNames;
use app\models\Posten;
use app\models\Groups;
use kartik\date\DatePicker;
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

<div class="tbl-bonuspunten-form">

    <?php

    // THIS FORM IS OBSOLETE!!!!
    // CAN BE REMOVED!!
dd('KAK OF TOCH NIET VERWIJDEREN?!');

    // Pjax::begin([
    //     'id' => 'bonuspunten-form-' . $model->bouspunten_ID,
    //     'enablePushState' => FALSE,
    // ]);
    //
    // echo AlertBlock::widget([
    //     'type' => AlertBlock::TYPE_ALERT,
    //     'useSessionFlash' => true,
    //     'delay' => 4000,
    // ]);

    $form = ActiveForm::begin([

//        'id' => 'event-names-LALALA',
        // 'options'=>[
        //     'data-pjax'=>TRUE,
        // ],
    ]); ?>
    <?php
    if($model->isNewRecord) {
        echo $form->field($model, 'group_ID',
        [
            'options' => [
                'id' => 'bonuspunten-group-field-create',
            ],
        ]
        )->dropDownList(
            Groups::getGroupOptionsForEvent(),
            [
                'prompt'=>'Select...',
                'id' => 'c'
            ]);
    }
    echo $form->field($model, 'date')->dropDownList(
        EventNames::getDatesAvailable(),
        [
            'prompt'=>'Select...',
            'id' => 'date-' . $model->bouspunten_ID
        ]);

    echo $form->field($model, 'post_ID')->dropDownList(
        Posten::getPostNameOptions(),
        [
            'prompt'=>'Select...',
            'id' => 'posten-group-field-creaate'
        ]);

    // EXAMPLE Dependent Dropdown
    // TODO dit werkt nog niet. Wordt niet bijgewerkt na het selecteren van een datum
//    echo $form->field($model, 'post_ID')->widget(DepDrop::classname(), [
//        'name' => 'post_ID',
//        'options' => ['id' => 'post_ID-' . $model->bouspunten_ID],
//        'pluginOptions' => [
//            'depends' => ['date-' . $model->bouspunten_ID],
//            'placeholder' => 'Select...',
//            'url' => Url::to(['/posten/lists-posts'])
//        ]
//    ]);

    echo $form->field($model, 'omschrijving')->textInput();
    echo $form->field($model, 'score')->textInput(['type' =>  'number']);
    ?>
    <div class="form-group">
        <?php
        if(!$model->isNewRecord) {

            echo Html::a(
                Yii::t('app', 'Opslaan'),
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
                Yii::t('app', 'Verwijderen'),
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
        if($model->isNewRecord) {
            echo Html::a(
                Yii::t('app', 'Opslaan'),
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
    // Pjax::end(); ?>

</div>
