<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblNoodEnvelop */

$this->title = Yii::t('app', 'Create Tbl Nood Envelop');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Nood Envelops'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-nood-envelop-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
