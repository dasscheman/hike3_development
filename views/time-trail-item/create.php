<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailItem */

$this->title = Yii::t('app', 'Tijdrit item toevoegen aan:') . ' ' . $time_trail_name;
?>
<div class="tbl-time-trail-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
