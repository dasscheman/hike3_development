<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OpenNoodEnvelop */

?>
<div class="tbl-open-vragen-antwoorden-beantwoorden">

    <h1><?= Html::encode(Yii::t('app', 'Awnser for {question}', ['question' => $modelVraag->open_vragen_name])) ?></h1>

    <?= $this->render('_form-beantwoorden', [
        'model' => $model,
        'modelVraag' => $modelVraag,
    ]) ?>
</div>
