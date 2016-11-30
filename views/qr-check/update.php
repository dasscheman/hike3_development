<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblQrCheck */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Qr Check',
]) . ' ' . $model->qr_check_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Qr Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->qr_check_ID, 'url' => ['view', 'id' => $model->qr_check_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-qr-check-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
