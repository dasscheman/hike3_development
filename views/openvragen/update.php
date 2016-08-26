<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragen */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Open Vragen',
]) . ' ' . $model->open_vragen_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Vragens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->open_vragen_ID, 'url' => ['view', 'id' => $model->open_vragen_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-open-vragen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
