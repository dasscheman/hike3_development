<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterMailList */

$this->title = Yii::t('app', 'Update Newsletter Mail List: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
?>
<div class="newsletter-mail-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
