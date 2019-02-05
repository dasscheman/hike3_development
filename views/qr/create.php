<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblQr */

$this->title = Yii::t('app', 'Stillepost toevoegen');
?>
<div class="tbl-qr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
