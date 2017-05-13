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

$this->title = Yii::t('app', 'Silent stations for') . ' ' . $model->route_name
?>
<div class="tbl-qr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Create new silent station'),
             'route'=>['qr/create', 'route_ID' => $model->route_ID],
             'modalId'=>'#main-modal',
             'modalContent'=>'#main-content-modal',
             'options'=>[
                 'class'=>'btn btn-success',
                 'title'=>'Button for create silent station',
                 'disabled' => !Yii::$app->user->identity->isActionAllowed('qr', 'create'),
             ]
         ]);
        ?>
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
            'emptyText' => Yii::t('app', 'There are no silent stations for this route section'),
        ]);
    ?>
</div>
