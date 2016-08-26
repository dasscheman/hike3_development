<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblGroups */

$this->title = Yii::t('app', 'Create Tbl Groups');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-groups-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
