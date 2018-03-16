<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-route-create">

    <h1><?= Html::encode(Yii::t(
        'app',
        'Import excel')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
