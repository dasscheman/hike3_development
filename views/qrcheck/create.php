<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblQrCheck */

$this->title = Yii::t('app', 'Create Tbl Qr Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Qr Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-qr-check-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
