<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenNoodEnvelop */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Open Nood Envelop',
]) . ' ' . $model->open_nood_envelop_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Nood Envelops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->open_nood_envelop_ID, 'url' => ['view', 'id' => $model->open_nood_envelop_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-open-nood-envelop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
