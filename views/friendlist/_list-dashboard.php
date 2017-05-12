<?php
use yii\helpers\Html;

use app\models\FriendList;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<!-- <div class="view"> -->

    <div class="well">

    <h3> <?php echo Yii::t('app', 'Friend request') ?> </h3>
    <b>
        <?php echo Html::encode($model->voornaam); ?>
        <?php echo Html::encode($model->achternaam); ?></br>
    </b>
    (<?php echo Html::encode($model->username); ?>)
    <br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('organisatie')); ?>:
    </b>
    <br>
    <?php echo Html::encode($model->organisatie); ?></br>
    <?php
    echo Html::a(
        Yii::t('app', 'Accept'),
        ['friend-list/accept', 'user_ID'=>$model->id],
        [
            'title' => Yii::t('app', 'Accept'),
            'class' =>'btn btn-success btn-xs',
        ]
    );

    echo Html::a(
        Yii::t('app', 'Decline'),
        ['friend-list/decline', 'user_ID'=>$model->id],
        [
            'title' => Yii::t('app', 'Decline'),
            'class' =>'btn btn-danger btn-xs',
        ]
    );
    ?>
    <br>
    <br>
</div>
