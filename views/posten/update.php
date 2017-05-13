<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Posten */

$this->title = Yii::t('app', 'Update {name}: ', [
    'name' => $model->post_name]);
?>
<div class="tbl-posten-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
