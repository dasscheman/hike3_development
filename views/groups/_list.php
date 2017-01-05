<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

use kartik\widgets\Select2;
/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="col-sm-3">
    <div class="row-1">
        <br>
        <br>
        <?php
        echo Html::a(
            $model->group_name,
            [
                '/groups/update',
                'id'=>$model->group_ID
            ],
            ['class'=>'btn btn-primary']);
    //    echo ButtonAjax::widget([
    //        'name' => $model->group_name,
    //        'route' => [
    //            '/groups/update',
    //            'event_id'=>$model->event_ID,
    //            'id'=>$model->group_ID
    //        ],
    //        'modalId' => '#group-update-modal-' . $model->group_name,
    //        'modalContent' => '#group-update-modal-' . $model->group_name,
    //        'options' => [
    ////            'id' => $model->group_name,
    //            'class' => 'btn btn-link',
    //            'title' => Yii::t('app', 'Edit group'),
    //            'disabled' => !Yii::$app->user->identity->isActionAllowed('groups', 'update'),
    //        ]
    //    ]);
        ?>

        <br/>
        <?php

        $printSeparator = false;
        foreach ($model->deelnemersEvents as $player )
        {
            if ($printSeparator){
                echo " - ";
            }
            echo $player->user->username;
    //        echo ButtonAjax::widget([
    //            'name' => $player->user->username,
    //            'route' => [
    //                'deelnemers-event/update',
    //                'id'=>$player->deelnemers_ID
    //            ],
    //            'modalId' => '#deelnemer-update-modal',
    //            'modalContent' => '#deelnemer-update-modal',
    //            'options' => [
    //                'class' => 'btn btn-link',
    //                'title' => Yii::t('app', 'Edit player'),
    //                'disabled' => !Yii::$app->user->identity->isActionAllowed('deelnemers-event', 'update'),
    //            ]
    //        ]);

            $printSeparator = true;
        }

    //    Modal::begin(['id' => 'group-update-modal-' . $model->group_name,
    //            'closeButton' => [
    //                'label' => 'Close',
    //                'class' => 'btn btn-danger btn-sm pull-right',
    //            ]]);
    //    echo '<div id="group-update-modal-"' . $model->group_name . '></div>';
    //    Modal::end();
    //    Modal::begin([
    //        'id' => 'deelnemer-update-modal',
    //        'options' => [
    //            'id' => 'deelnemer-update-modal',
    //            'tabindex' => false // important for Select2 to work properly
    //        ],
    //        'closeButton' => [
    //            'label' => 'Close',
    //            'class' => 'btn btn-danger btn-sm pull-right',
    //        ]]);
    //    echo '<div id="deelnemer-update-modal"></div>';
    //    Modal::end();

    //    Modal::begin(
    //    [
    //        'id' =>'modal-create-player',
    //        'options' => [
    //            'id' => 'modal-create-player',
    //            'tabindex' => TRUE // important for Select2 to work properly
    //        ],
    //        'toggleButton' => [
    //            'label' => Yii::t('app', 'Add plyer to group'),
    //            'class' => 'btn btn-success pull-right btn-sm',
    //            'disabled' => !Yii::$app->user->identity->isActionAllowed('deelnemers-event', 'create'),
    //            'size' => 'sm',
    //        ],
    //        'closeButton' => [
    //            'label' => 'Close',
    //            'class' => 'btn btn-danger btn-sm pull-right',
    //        ],
    //        'size' => Modal::SIZE_LARGE,
    //    //'options' => ['class'=>'slide'],
    //    ]);
    //    $modelNewDeelnemer = new app\models\DeelnemersEvent();
    //    echo $this->render('/deelnemers-event/_form2', ['model' => $modelNewDeelnemer, 'rol' => 'player']);
    //    Modal::end();?>
    </div>
<br>
</div>