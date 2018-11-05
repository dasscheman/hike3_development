<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */

?>
<div class="tbl-nood-envelop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
