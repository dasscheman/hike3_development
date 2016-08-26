<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblPosten */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Posten',
]) . ' ' . $model->post_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Postens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post_ID, 'url' => ['view', 'id' => $model->post_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-posten-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
