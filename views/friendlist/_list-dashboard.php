<?php
use yii\helpers\Html;

use app\models\FriendList;

/* @var $this GroupsController */
/* @var $data Groups */

?>
    <div class="well">

    <h3> <?php echo Yii::t('app', 'Friend request') ?> </h3>
    <b>
        <?php echo Html::encode($model->friendsWithUser->voornaam); ?>
        <?php echo Html::encode($model->friendsWithUser->achternaam); ?></br>
    </b>
        (<?php echo Html::encode($model->friendsWithUser->username); ?>)
    <br>
    <b>
        <?php echo Html::encode($model->friendsWithUser->getAttributeLabel('organisatie')); ?>:
    </b>
    <br>
    <?php echo Html::encode($model->friendsWithUser->organisatie); ?></br>
    <?php
    echo Html::a(
        Yii::t('app', 'Accept'),
        [
            'friend-list/accept',
            'friend_list_ID'=>$model->friend_list_ID

        ],
        [
            'title' => Yii::t('app', 'Accept'),
            'class' =>'btn btn-success btn-xs',
        ]
    );

    echo Html::a(
        Yii::t('app', 'Decline'),
        ['friend-list/decline', 'friend_list_ID'=>$model->friend_list_ID],
        [
            'title' => Yii::t('app', 'Decline'),
            'class' =>'btn btn-danger btn-xs',
        ]
    );
    ?>
    <br>
    <br>
</div>
