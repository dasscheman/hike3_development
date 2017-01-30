<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QrCheckSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview checked silent stations');
?>
<div class="qr-check-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
            'attribute' => 'qr_name',
            'value' => 'qr.qr_name'
            ],
            [
            'attribute' => 'group_name',
            'value' => 'group.group_name'
            ],
            [
            'attribute' => 'route_name',
            'value' => 'qr.route.route_name'
            ],
            'create_time',
            [
            'attribute' => 'score',
            'value' => 'qr.score'
            ],
            [
            'attribute' => 'username',
            'value' => 'createUser.username'
            ],
        ],
    ]); ?>

</div>
