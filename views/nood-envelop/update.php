<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Nood Envelop',
]) . ' ' . $model->nood_envelop_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Nood Envelops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nood_envelop_ID, 'url' => ['view', 'id' => $model->nood_envelop_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-nood-envelop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
