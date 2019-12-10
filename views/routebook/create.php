<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Routebook */

$this->title = Yii::t('app', 'Update routeboek {routeName}: ', [
    'routeName' => '$model->route_name']);
?>
<div class="routebook-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
