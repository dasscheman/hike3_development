<?php

/* @var $this UsersController */
/* @var $model Users */

$this->title = Yii::t('app', 'Forgot password?');
?>
<div class="users-create">

    <?= $this->render('_formGetNewPass', [
        'model' => $model,
    ]) ?>

</div>