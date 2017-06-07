<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TblPostPassage */

$this->title = Yii::t('app', 'Update');
?>
<div class="tbl-post-passage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>
