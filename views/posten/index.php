<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblPostenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Postens');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-posten-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Posten'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'post_ID',
            'post_name',
            'event_ID',
            'date',
            'post_volgorde',
            // 'score',
            // 'create_time',
            // 'create_user_ID',
            // 'update_time',
            // 'update_user_ID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
