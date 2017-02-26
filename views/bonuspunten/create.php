<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspunten */

$this->title = Yii::t('app', 'Assign Bonuspoints');
?>
<div class="tbl-bonuspunten-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form-dashboard', [
        'model' => $model,
    ]) ?>

</div>
