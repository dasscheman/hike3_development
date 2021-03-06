<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\widgets\ListView;
use app\models\Qr;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;
use yii\data\ArrayDataProvider;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-qr-view">

    <h1><?= Html::encode(Yii::t('app', 'Stilleposten')) ?></h1>

    <?php

        $pages = new yii\data\Pagination(['pageSize' => 100]);
        $dataProvider = new ArrayDataProvider([
          'allModels' => $model->qrs,
          'pagination' => $pages,
        ]);

        echo ListView::widget([
            'summary' => FALSE,
            'pager' => FALSE,
            'dataProvider' => $dataProvider,
            'itemView' => '/qr/_list',
            'emptyText' => Yii::t('app', 'There are no silent stations for this route section'),
        ]);
    ?>
</div>
