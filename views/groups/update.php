<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

$this->title = Yii::t('app', 'Update {groupName}: ', [
    'groupName' => $model->group_name,
]);
?>
<div class="tbl-groups-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
