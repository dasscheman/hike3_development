<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Routebook */

$this->title = Yii::t('app', 'Create routeboek {routeName}: ', [
    'routeName' => $model->route->route_name]);
?>
<div class="routebook-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
