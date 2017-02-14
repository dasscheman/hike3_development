<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

use app\models\Users;
use app\models\Groups;
use kartik\widgets\Select2;
/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <?php echo $model['groupname']; ?>
            (<?php echo $model['username']; ?>)
            <!-- <img src="bandmember.jpg" class="img-circle" height="55" width="55" alt="Avatar"> -->

            <h4>
                <?php echo $model['title'];?>
            </h4>
            <?php echo $model['description'];?>
            -
            <?php echo $model['timestamp']; ?>
        </div>
    </div>
</div>
