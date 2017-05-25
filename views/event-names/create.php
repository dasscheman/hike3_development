<?php

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */

$this->title = Yii::t('app', 'Create new hike');
?>
<div class="tbl-event-names-form">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'create'
    ]) ?>
</div>
