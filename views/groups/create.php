<?php

use yii\helpers\Html;
use app\models\FriendList;

/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

?>
<div class="tbl-groups-create">

    <h1><?= Html::encode(Yii::t('app', 'Create new group')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'friendList' => new FriendList()
    ]) ?>

</div>
