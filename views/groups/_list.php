<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

use kartik\widgets\Select2;
/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="row">
  <div class="col-sm-12">
    <div class="well">
        <p>
        <?php
        echo Html::a(
            $model->group_name,
            [
                '/groups/update',
                'group_ID'=>$model->group_ID
            ],
            ['class'=>'btn btn-xs btn-primary']);
        ?>
        <br/>
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
        </p>
      </div>
    </div>
</div>
