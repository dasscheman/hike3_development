<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EventNames */

?>
<div class="tbl-event-names-update">

    <h1><?= Html::encode(
        Yii::t('app', 'Hike {hike} bijwerken', [
            'hike' => $model->event_name])
    )?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>
