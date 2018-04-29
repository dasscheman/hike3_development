<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Newsletter */

$this->title = Yii::t('app', 'Update Newsletter: {nameAttribute}', [
   'nameAttribute' => $model->id,
]);
?>
<div class="newsletter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
