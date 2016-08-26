<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Users'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_ID',
            'username',
            'voornaam',
            'achternaam',
            'organisatie',
            // 'email:email',
            // 'password',
            // 'macadres',
            // 'birthdate',
            // 'last_login_time',
            // 'create_time',
            // 'create_user_ID',
            // 'update_time',
            // 'update_user_ID',
            // 'selected_event_ID',
            // 'authKey',
            // 'accessToken',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
