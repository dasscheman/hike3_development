<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblOpenNoodEnvelop */

?>
<div class="tbl-open-nood-envelop-create">

    <h1><?= Html::encode(Yii::t('app', 'Open hint') . ' ' . $modelEnvelop->nood_envelop_name) ?></h1>

    <?= $this->render('_form-open', [
        'model' => $model,
        'modelEvent' => $modelEnvelop,
    ]) ?>

</div>
