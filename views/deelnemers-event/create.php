<?php

use yii\helpers\Html;
use app\models\FriendList;
use app\models\DeelnemersEvent;


/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */

?>
<div class="tbl-deelnemers-event-create">

    <h1><?= Html::encode(Yii::t('app', 'Voeg organisator toe aan hke')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'friendList' => new FriendList(),
        'deelnemersEvent' => new DeelnemersEvent(),
    ]) ?>

</div>
