<?php

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\DateTimePicker;
use app\models\Groups;

/* @var $this yii\web\View */
/* @var $model app\models\PostPassage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-post-passage-form">
    <?php
    Pjax::begin([
        'id' => 'post-passage-form-' . $model->posten_passage_ID,
        'enablePushState' => false
    ]);
    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]);
    $form = ActiveForm::begin([
        'options'=>[
            'data-pjax'=>TRUE,
        ],
    ]); ?>

    <h3>
        <?php echo Html::encode($model->post->post_name); ?>
    </h3> <?php
    echo $form->field($model, 'gepasseerd')->checkBox(['uncheck' => FALSE, 'selected' => TRUE]);

    echo $form->field($model, 'binnenkomst')->widget(
        DateTimePicker::classname(), [
        	'options' => ['placeholder' => Yii::t('app', 'Enter incheck time')],
            'type' => DateTimePicker::TYPE_INPUT,
        	'pluginOptions' => [
        		'autoclose' => true
        	]
    ]);

    echo  $form->field($model, 'vertrek')->widget(
    DateTimePicker::classname(), [
    	'options' => ['placeholder' => Yii::t('app', 'Enter leave time')],
        'type' => DateTimePicker::TYPE_INPUT,
    	'pluginOptions' => [
    		'autoclose' => true
    	]
    ]);
    ?>

    <div class="form-group">
        <?php

        echo Html::a(
            Yii::t('app', 'Save'),
            ['/post-passage/update', 'id' => $model->posten_passage_ID],
            ['class' => 'btn btn-xs btn-success'],
            ['data-pjax' => 'post-passage-list-' . $model->posten_passage_ID]
        );

        echo Html::a(
            Yii::t('app', 'Delete'),
            ['/post-passage/delete', 'id' => $model->posten_passage_ID],
            ['class' => 'btn btn-xs btn-danger'],
            ['data-pjax' => 'post-passage-list-' . $model->posten_passage_ID]
        );

        echo Html::a(
            Yii::t('app', 'Cancel'),
            ['/post-passage/cancel', 'id' => $model->posten_passage_ID],
            ['class' => 'btn btn-xs btn-primary'],
            ['data-pjax' => 'post-passage-list-' . $model->posten_passage_ID]
        ); ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end(); ?>

</div>
