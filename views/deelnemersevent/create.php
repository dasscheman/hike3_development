<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblDeelnemersEvent */

$this->title = Yii::t('app', 'Create Tbl Deelnemers Event');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Deelnemers Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-deelnemers-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
