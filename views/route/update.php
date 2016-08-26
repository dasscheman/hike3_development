<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblRoute */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Route',
]) . ' ' . $model->route_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Routes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->route_ID, 'url' => ['view', 'id' => $model->route_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-route-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
