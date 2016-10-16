<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\widgets\ListView;
use app\models\Qr;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Modal;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

$this->title = 'Qr\'s for' . ' ' . $model->route_name;
?>
<div class="tbl-route-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create new qr'), ['update', 'id' => $model->route_ID], ['class' => 'btn btn-primary']) ?>
    </p>
    <?php

    $query = Qr::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
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
