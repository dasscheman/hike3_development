<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-route-create">

    <h1><?= Html::encode(Yii::t(
        'app',
        'Create new route for {day}.',
        ['day' => ($model->day_date === '0000-00-00')?Yii::t('app', 'Introduction'):$model->day_date])) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
