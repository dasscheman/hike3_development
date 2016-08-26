<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Groups',
]) . ' ' . $model->group_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group_ID, 'url' => ['view', 'id' => $model->group_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-groups-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
