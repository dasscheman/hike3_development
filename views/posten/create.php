<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblPosten */

$this->title = Yii::t('app', 'Create Tbl Posten');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Postens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-posten-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
