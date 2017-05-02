<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->title = Yii::t('app', 'Create new route for' . ' ' . $model->day_date );

?>
<div class="tbl-route-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
