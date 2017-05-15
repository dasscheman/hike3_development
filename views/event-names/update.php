<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */

$this->title = Yii::t('app', 'Hike {hike} bijwerken', [
    'hike' => $model->event_name,
]);
?>
<div class="tbl-event-names-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>
