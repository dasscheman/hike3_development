<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblEventNames */

$this->title = Yii::t('app', 'Hike {modelClass} bijwerken', [
    'modelClass' => $model->event_name,
]);
?>
<div class="tbl-event-names-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
