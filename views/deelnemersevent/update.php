<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblDeelnemersEvent */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Deelnemers Event',
]) . ' ' . $model->deelnemers_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Deelnemers Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->deelnemers_ID, 'url' => ['view', 'id' => $model->deelnemers_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-deelnemers-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
