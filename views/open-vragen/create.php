<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OpenVragen */

$this->title = Yii::t('app', 'Vragen toevoegen');
?>
<div class="tbl-open-vragen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
