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

$this->title = Yii::t('app', 'Questions') . ' ' . $model->route_name;
?>
<div class="tbl-open-vragen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Create new question'),
             'route'=>['open-vragen/create', ['route_id' => $model->route_ID]],
             'modalId'=>'#main-modal',
             'modalContent'=>'#main-content-modal',
             'options'=>[
                 'class'=>'btn btn-success',
                 'title'=>'Button for create application',
             ]
         ]);
        ?>
    </p>
    <?php

    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->openVragens,
    ]);

    yii\widgets\Pjax::begin(['id' => 'route-vragen-view', 'enablePushState' => TRUE]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/open-vragen/_list',
        'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
    ]);

    yii\widgets\Pjax::end();
?>
</div>
