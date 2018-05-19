<?php
use app\models\Groups;

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
            <?php echo $model['timestamp']; ?>
        </div>
    </div>
</div>
