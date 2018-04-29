<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Time Trail Item',
]) . $model->time_trail_item_name;
?>
<div class="time-trail-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
