<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

$this->title = Yii::t('app', 'Create Tbl Post Passage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbl Post Passages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-post-passage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
