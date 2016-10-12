<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'View friend requests');
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
            'username',
            'voornaam',
            'achternaam',
            'organisatie',
            'email',
             'birthdate',
            'last_login_time',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{accept} {decline}',
                'buttons' => [
                    'accept' => function ($url, $model) {
                        return Html::a( 
                            Yii::t('app', 'Accept'),
                            ['friend-list/accept', 'user_id'=>$model->id],
                            [ 
                                'title' => Yii::t('app', 'Accept'),
                                'class' =>'btn btn-success btn-xs', 
                            ]
                        ); 
                    },
                    'decline' => function ($url, $model) {
                        return Html::a( 
                            Yii::t('app', 'Decline'),
                            ['friend-list/decline', 'user_id'=>$model->id],
                            [ 
                                'title' => Yii::t('app', 'Decline'),
                                'class' =>'btn btn-danger btn-xs', 
                            ]
                        ); 
                    }
                ],
            ],
        ],
    ]); ?>
<?php yii\widgets\Pjax::end(); ?>
</div>
