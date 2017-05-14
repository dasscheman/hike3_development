<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragenAntwoorden */

$this->title = Yii::t('app', 'Awnser question');
?>
<div class="tbl-open-vragen-antwoorden-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-dashboard', [
        'model' => $model,
        'modelVraag' => $modelVraag,
    ]) ?>

</div>
