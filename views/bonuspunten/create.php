<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblBonuspunten */

$this->title = Yii::t('app', 'Create Tbl Bonuspunten');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Bonuspuntens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-bonuspunten-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
