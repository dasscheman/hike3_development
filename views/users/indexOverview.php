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
    ]); 
    
    $this->widget('bootstrap.widgets.TbTabs', array(
        'tabs'=>array(
            array(
                'id'=>'tab1',
                'active'=>true,
                'label'=>'Thuis',
                'content'=>$this->renderPartial("/users/_view", array('data' => $userData),true),
            ),
            array(
                'label' => 'Vrienden',
                'content' =>"Dit is een lijst met vrienden".$this->widget(
                    'bootstrap.widgets.TbGridView',
                    array(
                       // 'id'=>'tab2',
						'id'=>'users-grid',
                        'dataProvider'=>$friendsData->searchFriends(),
                        'filter'=>$friendsData,
                        'columns'=>array(
                            'username',
                            'voornaam',
                            'achternaam',
							'organisatie',
                            'email',),),
                    true
                ),
            ),
            array(
                'label' => 'Verzoeken',
                'content' =>"Dit is een lijst met mensen die jou een vriendschapsverzoek hebben gedaan".$this->widget(
                'bootstrap.widgets.TbGridView',
                    array(
                        'id'=>'tab3',
                        'dataProvider'=>$pendingFriendsData->searchPending(),
                        'filter'=>$pendingFriendsData,
                        'columns'=>array(
                            'username',
                            'voornaam',
                            'achternaam',
							'organisatie',
                            'email',
                            array(
                                'header'=>'accepteer',
                                'class'=>'CButtonColumn',
                                'template'=>'{accept} {decline}',
                                'buttons'=>array(
                                    'accept' => array(
                                        'label'=>'
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-check fa-stack-1x"></i>
                                        </span>',
                                        'url'=>'Yii::app()->createUrl("friendList/accept", array("user_id"=>$data->user_ID))',
                                        'visible'=>'FriendList::model()->isActionAllowed("friendList", "accept", "", $data->user_ID)',
                                    ),
                                    'decline' => array(
                                        'label'=>'
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-ban fa-stack-1x"></i>
                                        </span>',
                                        'url'=>'Yii::app()->createUrl("friendList/decline", array("user_id"=>$data->user_ID))',
                                        'visible'=>'FriendList::model()->isActionAllowed("friendList", "decline", "", $data->user_ID )',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    true
                ),
            ),
            array(
                'label' => 'Hikes',
                'content' =>"Dit is een lijst met hikes die jij gestart bent".$this->widget(
                    'bootstrap.widgets.TbGridView',
                    array(
                        'id'=>'tab4',
                        'dataProvider'=>$hikeData->search(),
                        //'filter'=>$hikeData,
                        'columns'=>array(
                            //'event_ID',
                            'event_name',
                            'start_date',
                            'end_date',
                            'status'=>array(
                                'name'=>'status',
                                'value'=>'$data->getStatusText()'),
                            'create_user'=>array(
                                'name'=>'create_user_ID',
                                'value'=>'Users::model()->getUserName($data->create_user_ID)'),
                            array(
                                'header'=>'Bekijk',
                                'class'=>'CButtonColumn',
                                'template'=>'{game} {startup}',
                                'buttons'=>array(
									'game' => array(
                                        'label'=>'<span class="fa-stack fa-lg">
													<i class="fa fa-circle fa-stack-1x fa-green fa-15x"></i>
													<i class="fa fa-compass fa-stack-1x"></i>
												</span>',
                                        'options'=>array('title'=>'Bekijk deze hike'),
                                        'url'=>'Yii::app()->createUrl("game/gameOverview", array("event_id"=>$data->event_ID))',
                                        'visible'=>'DeelnemersEvent::model()->isActionAllowed(
                                            "game",
                                            "gameOverview",
                                            $data->event_ID)'
									),
                                    'startup' => array(
                                        'label'=>'<span class="fa-stack fa-lg">
													<i class="fa fa-circle fa-stack-1x fa-green fa-15x"></i>
													<i class="fa fa-tachometer fa-stack-1x"></i>
												</span>',
                                        'options'=>array('title'=>'Bekijk de settings van deze hike'),
                                        'url'=>'Yii::app()->createUrl("startup/startupOverview", array("event_id"=>$data->event_ID))',
                                        'visible'=>'DeelnemersEvent::model()->isActionAllowed(
                                            "startup",
                                            "startupOverview",
                                            $data->event_ID)'
                                    ),
                                ),
                            ),
                        ),
                    ),
                    true
                ),
            ),
        ),
    ));





?>
</div>

    ?>

</div>
