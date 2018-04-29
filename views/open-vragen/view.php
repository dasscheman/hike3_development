<?php

use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-vragen-view">

    <h1><?= Html::encode(Yii::t('app', 'Questions for {routename}', ['routename' => $model->route_name])) ?></h1>

    <?php
        $dataProvider = new yii\data\ArrayDataProvider([
            'allModels' => $model->openVragens,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/open-vragen/_list',
            'emptyText' => Yii::t('app', 'There are no questions for this route section'),
        ]);
    ?>
</div>
