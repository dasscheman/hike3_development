<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblEventNamesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Event Names');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-event-names-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Event Names'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'event_ID',
            'event_name',
            'start_date',
            'end_date',
            'status',
            // 'active_day',
            // 'max_time',
            // 'image',
            // 'organisatie',
            // 'website',
            // 'create_time',
            // 'create_user_ID',
            // 'update_time',
            // 'update_user_ID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
