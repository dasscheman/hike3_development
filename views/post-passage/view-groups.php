<?php

use yii\widgets\ListView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\PostPassage */

$this->title = Yii::t('app', 'Stations');
?>
<div class="tbl-post-passage-view-groups">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $groups,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/post-passage/_list-groups',
            'viewParams' => ['post_id' => $post_id],
            'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
        ]);
    ?>
</div>
