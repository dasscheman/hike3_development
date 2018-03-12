<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use prawee\widgets\ButtonAjax;
/* @var $this yii\web\View */
/* @var $model app\models\Route */

?>
<div class="tbl-nood-envelop-view">

    <h1><?= Html::encode(Yii::t('app', 'Hints for') . ' ' . $model->route_name) ?></h1>

    <p>
        <?php
        echo ButtonAjax::widget([
            'name'=>Yii::t('app', 'Create new hint'),
             'route'=>['nood-envelop/create', 'route_ID' => $model->route_ID],
             'modalId'=>'#main-modal',
             'modalContent'=>'#main-content-modal',
             'options'=>[
                 'class'=>'btn btn-success',
                 'title'=>'Button for create application',
                 'disabled' => !Yii::$app->user->can('organisatieIntrodutie') && !Yii::$app->user->can('organisatieOpstart'),
             ]
         ]);
        ?>
    </p>
    <?php

    $dataProvider = new yii\data\ArrayDataProvider([
        'allModels' => $model->noodEnvelops,
    ]);

    echo ListView::widget([
        'summary' => FALSE,
        'pager' => FALSE,
        'dataProvider' => $dataProvider,
        'itemView' => '/nood-envelop/_list',
        'emptyText' => Yii::t('app', 'There are no hints for this route section'),
    ]);
?>
</div>
