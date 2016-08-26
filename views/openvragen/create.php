<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragen */

$this->title = Yii::t('app', 'Create Tbl Open Vragen');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Vragens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-open-vragen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>