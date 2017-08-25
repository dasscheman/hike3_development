<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Time Trail Item',
]) . $model->time_trail_item_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Time Trail Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->time_trail_item_ID, 'url' => ['view', 'id' => $model->time_trail_item_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="time-trail-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
