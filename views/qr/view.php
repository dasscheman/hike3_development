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

    <h1><?= Html::encode(Yii::t('app', 'Stilleposten voor {routename}', ['routename' => $model->route_name])) ?></h1>

    <?php

        // EXAMPLE
        $dataProvider = new yii\data\ArrayDataProvider([
            'allModels' => $model->qrs,
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
