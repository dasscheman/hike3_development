<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OpenVragenAntwoordenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Overview answered questions');
?>
<div class="open-vragen-antwoorden-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
            'attribute' => 'open_vragen_name',
            'value' => 'openVragen.open_vragen_name'
            ],
            [
            'attribute' => 'group_name',
            'value' => 'group.group_name'
            ],
            [
            'attribute' => 'route_name',
            'value' => 'openVragen.route.route_name'
            ],
            'create_time',
            [
            'attribute' => 'score',
            'value' => 'openVragen.score'
            ],
            [
            'attribute' => 'username',
            'value' => 'createUser.username'
            ],
        ],
    ]); ?>

</div>
