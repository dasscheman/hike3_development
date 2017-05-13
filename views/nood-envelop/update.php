<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NoodEnvelop */

$this->title = Yii::t('app', 'Edit {name} ', [
    'name' => $model->nood_envelop_name]);
?>
<div class="tbl-nood-envelop-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
