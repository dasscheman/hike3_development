<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */

?>
<div class="tbl-deelnemers-event-create">

    <h1><?= Html::encode(Yii::t('app', 'Add organisation to hike')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
