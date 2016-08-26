<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblQr */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Qr',
]) . ' ' . $model->qr_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Qrs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->qr_ID, 'url' => ['view', 'id' => $model->qr_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-qr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
