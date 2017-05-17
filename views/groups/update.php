<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

?>
<div class="tbl-groups-update">

    <h1><?= Html::encode(Yii::t('app', 'Update {groupName}: ', [
        'groupName' => $model->group_name,
    ])) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
