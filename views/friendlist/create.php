<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblFriendList */

$this->title = Yii::t('app', 'Create Tbl Friend List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Friend Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-friend-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
