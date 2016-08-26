<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragenAntwoorden */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Open Vragen Antwoorden',
]) . ' ' . $model->open_vragen_antwoorden_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Vragen Antwoordens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->open_vragen_antwoorden_ID, 'url' => ['view', 'id' => $model->open_vragen_antwoorden_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-open-vragen-antwoorden-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
