<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblEventNames */

$this->title = Yii::t('app', 'Nieuwe hike maken');
?>
<div class="tbl-event-names-form">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
