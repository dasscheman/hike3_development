<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblRoute */

$this->title = Yii::t('app', 'Create Tbl Route');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Routes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
