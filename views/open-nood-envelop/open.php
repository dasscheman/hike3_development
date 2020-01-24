<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OpenNoodEnvelop */
?>
<div class="tbl-open-nood-envelop-open">

    <h1><?= Html::encode(Yii::t('app', 'Open hint') . ' ' . $modelEnvelop->nood_envelop_name) ?></h1>

    <?= $this->render('_form-open', [
        'model' => $model,
        'modelEnvelop' => $modelEnvelop,
    ]) ?>
</div>
