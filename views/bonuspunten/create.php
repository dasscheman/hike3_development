<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspunten */

?>
<div class="tbl-bonuspunten-create">

    <h1><?= Html::encode(Yii::t('app', 'Assign Bonuspoints')) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
