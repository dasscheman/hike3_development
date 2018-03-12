<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Search for new friends');
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php yii\widgets\Pjax::begin(['id' => 'searchfriends', 'enablePushState' => false]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'voornaam',
            'achternaam',
            'organisatie',
            'email',
             'birthdate',
            'last_login_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{connect}',
                'buttons' => [
                    'connect' => function ($url, $model) {
                        return Html::a(
                            Yii::t('app', 'Invite'),
                            ['friend-list/connect', 'user_ID'=>$model->id],
                            [
                                'title' => Yii::t('app', 'Invite'),
                                'class' =>'btn btn-primary btn-xs',
                            ]
                        );
                    }
                ],
            ],
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>
</div>
