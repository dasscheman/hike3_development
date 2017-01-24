<?php

use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-open-vragen-view">

    <h1><?= Html::encode(Yii::t('app', 'Questions for') . ' ' . $model->route_name) ?></h1>

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

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/open-vragen/_list',
        'emptyText' => Yii::t('app', 'There are no questions for this route section'),
    ]);

?>
</div>
