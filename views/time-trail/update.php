<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrail */

$this->title = Yii::t('app', 'Update Time trail');
?>
<div class="time-trail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
