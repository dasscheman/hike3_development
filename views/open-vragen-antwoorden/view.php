<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-vragen-view">

    <h1><?= Html::encode(Yii::t('app', 'Questions for') . ' ' . $model->route_name) ?></h1>
    <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $model->openVragens,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/open-vragen-antwoorden/_list',
            'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
        ]);
    ?>
</div>
