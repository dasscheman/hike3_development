<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragen */

$this->title = Yii::t('app', 'Edit {modelClass} ', [
    'modelClass' => $model->open_vragen_name]);
?>
<div class="tbl-open-vragen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
