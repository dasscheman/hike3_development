<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblOpenVragenAntwoordenSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbl Open Vragen Antwoordens');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-open-vragen-antwoorden-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tbl Open Vragen Antwoorden'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'open_vragen_antwoorden_ID',
            'open_vragen_ID',
            'event_ID',
            'group_ID',
            'antwoord_spelers',
            // 'checked',
            // 'correct',
            // 'create_time',
            // 'create_user_ID',
            // 'update_time',
            // 'update_user_ID',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
