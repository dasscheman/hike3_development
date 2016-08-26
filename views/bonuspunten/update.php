<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspunten */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Bonuspunten',
]) . ' ' . $model->bouspunten_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bonuspuntens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->bouspunten_ID, 'url' => ['view', 'id' => $model->bouspunten_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-bonuspunten-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
