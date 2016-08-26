<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tbl Post Passage',
]) . ' ' . $model->posten_passage_ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Post Passages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->posten_passage_ID, 'url' => ['view', 'id' => $model->posten_passage_ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tbl-post-passage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
