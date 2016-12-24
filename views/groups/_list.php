<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">
    
	<b>
        <?php echo Html::encode($model->getAttributeLabel('group_name')); ?>:
    </b>
    <?php
    echo ButtonAjax::widget([
        'name' => $model->group_name,
        'route' => [
            '/groups/update',
            'event_id'=>$model->event_ID,
            'id'=>$model->group_ID
        ],
        'modalId' => '#group-update-modal',
        'modalContent' => '#group-update-modal',
        'options' => [
            'class' => 'btn btn-link',
            'title' => Yii::t('app', 'Edit group'),
        ]
    ]);
    ?>

    <br/>
	<?php

	$printSeparator = false;
	foreach ($model->deelnemersEvents as $player )
	{
		if ($printSeparator){
            echo " - ";
        }
        echo ButtonAjax::widget([
            'name' => $player->user->voornaam,
            'route' => ['deelnemers-event/delete'],
            'modalId' => '#user-delete-modal',
            'modalContent' => '#user-delete-modal',
            'options' => [
                'class' => 'btn btn-link',
                'title' => Yii::t('app', 'Remove player'),
            ]
        ]);

		$printSeparator = true;
	}
    
    echo ButtonAjax::widget([
        'name' => Yii::t('app', 'Add player to group'),
        'route' => ['deelnemers-event/_formAdd'],
        'modalId' => '#user-add-modal',
        'modalContent' => '#user-add-modal',
        'options' => [
            'class' => 'btn btn-link',
            'title' => Yii::t('app', 'Add player'),
        ]
    ]);

    Modal::begin(['id' => 'group-update-modal']);
    echo '<div id="group-update-modal"></div>';
    Modal::end();
    Modal::begin(['id' => 'user-delete-modal']);
    echo '<div id="users-delete-modal"></div>';
    Modal::end();
    Modal::begin(['id' => 'user-add-modal']);
    echo '<div id="user-add-modal"></div>';
    Modal::end(); ?>
    
    </div>
</div>