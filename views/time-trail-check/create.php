<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TimeTrailCheck */

$this->title = Yii::t('app', 'Create Time Trail Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Time Trail Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-trail-check-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
