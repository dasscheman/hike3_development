<?php

use yii\helpers\Html;
use app\models\FriendList;

/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

?>
<div class="tbl-groups-update">

    <h1><?= Html::encode(Yii::t('app', 'Update {groupName}: ', [
        'groupName' => $model->group_name,
    ])) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'friendList' => new FriendList()
    ]) ?>

</div>
