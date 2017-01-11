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
            $printSeparator = true;
        }?>
    </div>
<br>
</div>