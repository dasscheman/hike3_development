<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NewsletterMailList */

$this->title = Yii::t('app', 'Create Newsletter Mail List');
?>
<div class="newsletter-mail-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
