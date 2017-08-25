<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailCheck */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Time Trail Check',
]) . $model->time_trail_check_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Time Trail Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->time_trail_check_ID, 'url' => ['view', 'id' => $model->time_trail_check_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="time-trail-check-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
