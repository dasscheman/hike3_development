<?php

use app\models\Groups;
use yii\helpers\Html;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <?php echo $model['groupname']; ?>
            (<?php echo $model['username']; ?>)
            <h4>
                <?php echo $model['title'];?>
            </h4>

            <?php
                echo Yii::$app->setupdatetime->displayFormat($model['timestamp'], 'datetime', false, true);
            ?>
        </div>
    </div>
</div>
