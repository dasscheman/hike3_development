<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblFriendList */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Friend List',
]) . ' ' . $model->friend_list_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Friend Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->friend_list_ID, 'url' => ['view', 'id' => $model->friend_list_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-friend-list-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
