<?php

use yii\helpers\Html;
use app\models\EventNames;
use app\models\Posten;
use app\models\Groups;


/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspunten */

?>
<div class="tbl-bonuspunten-create">

    <h1><?= Html::encode(Yii::t('app', 'Bonuspunten toevoegen')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'groups' => new Groups(),
        'eventNames' => new EventNames()
    ]) ?>

</div>
