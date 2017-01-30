<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OpenNoodEnvelopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview opened hints');
?>
<div class="open-nood-envelop-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => FALSE,
        'columns' => [
            [
            'attribute' => 'nood_envelop_name',
            'value' => 'noodEnvelop.nood_envelop_name'
            ],
            [
            'attribute' => 'group_name',
            'value' => 'group.group_name'
            ],
            [
            'attribute' => 'route_name',
            'value' => 'noodEnvelop.route.route_name'
            ],
            'create_time',
            [
            'attribute' => 'score',
            'value' => 'noodEnvelop.score'
            ],
            [
            'attribute' => 'username',
            'value' => 'createUser.username'
            ],  
        ],
    ]); ?>

</div>
