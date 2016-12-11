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

$this->title = 'Qr\'s for' . ' ' . $model->route_name;
?>
<div class="tbl-qr-view" <?php $model->route_ID ?>>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create new qr'), ['update', 'id' => $model->route_ID], ['class' => 'btn btn-primary']) ?>
    </p>
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
        'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
    ]);
?>
</div>
