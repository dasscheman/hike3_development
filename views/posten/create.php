<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Posten */

?>
<div class="tbl-posten-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
