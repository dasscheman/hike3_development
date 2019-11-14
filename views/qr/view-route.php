<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-qr-view">

    <h3><?= Html::encode(Yii::t('app', 'Stilleposten')) ?></h3>
    <p>
        <?php
        Pjax::begin(['id' => 'qr-view-route-' . $route_id, 'enablePushState' => false]);
        ?>
    </p>
    <?php
        echo ListView::widget([
            'summary' => FALSE,
            'pager' => [
                'prevPageLabel' => Yii::t('app', 'previous'),
                'nextPageLabel' => Yii::t('app', 'next'),
                'maxButtonCount' => 0,
                'options' => [
                   'class' => 'pagination pagination-sm',
                ],
            ],
            'dataProvider' => $model,
            'itemView' => '/qr/_list-dashboard',
            'emptyText' => Yii::t('app', 'Geen stilleposten gescand.'),
        ]);
    ?>
    <?php Pjax::end(); ?>
</div>


        <!-- $pages = new yii\data\Pagination(['pageSize' => 100]);
        $dataProvider = new ArrayDataProvider([
          'allModels' => $model,
          'pagination' => $pages,
        ]); -->
