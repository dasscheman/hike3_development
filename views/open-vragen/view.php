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

$this->title = Yii::t('app', 'Questions for') . ' ' . $model->open_vragen_name;
?>
<div class="tbl-open-vragen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Create new question'),
             'route'=>['open-vraag/create'],
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

    $query = app\models\OpenVragen::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/open-vragen/_list',
        'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
    ]);
?>
</div>
