<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */

$this->title = Yii::t('app', 'Add organisation to hike');
?>
<div class="tbl-deelnemers-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
