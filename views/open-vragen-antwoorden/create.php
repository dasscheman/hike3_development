<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblOpenVragenAntwoorden */

$this->title = Yii::t('app', 'Create Tbl Open Vragen Antwoorden');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Open Vragen Antwoordens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-open-vragen-antwoorden-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
