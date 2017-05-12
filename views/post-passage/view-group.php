<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\PostPassage */

$this->title = Yii::t('app', 'Stations passed by') . ' ' . $model->group_name;
?>
<div class="tbl-post-passage-view-group">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->postPassages,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/post-passage/_list',
            'emptyText' => 'Er zijn nog geen groepen ingechecked op deze post.',
        ]);
    ?>
</div>
