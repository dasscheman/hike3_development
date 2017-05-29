<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */

$this->title = Yii::t('app', 'Edit user {username}', ['username' => $model->user->voornaam . ' ' . $model->user->achternaam]);
?>
<div class="tbl-deelnemers-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
