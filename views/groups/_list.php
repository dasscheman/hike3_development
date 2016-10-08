<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="view">
    
	<b><?php echo Html::encode($model->getAttributeLabel('group_name')); ?>:</b>
	<?php echo Html::a(Html::encode($model->group_name), array(	'/groups/update',
																	'event_id'=>$model->event_ID,
																	'id'=>$model->group_ID)); ?>
	<br />
	<?php

	$printSeparator = false;
	foreach ($model->deelnemersEvents as $player )
	{
		if ($printSeparator)
			echo " - ";
		echo CHtml::link(CHtml::encode(Users::model()->getUserName($player->user_ID)),
			   array('/deelnemersEvent/update',
				 'event_id'=>$player->event_ID,
				 'id'=>$player->deelnemers_ID));
		echo CHtml::encode(Users::model()->getUserName($player->user_ID));
		$printSeparator = true;
	}
    
    echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Add player to group'),
            'route' => ['deelnemers-event/create'],
            'modalId' => '#tempmain-modal',
            'modalContent' => '#players-content-modal',
            'options' => [
                'class' => 'btn btn-link',
                'title' => 'Button for create application',
            ]
        ]);

    Modal::begin(['id' => 'tempmain-modal']);
    echo '<div id="players-content-modal"></div>';
    Modal::end(); ?>
    
    </div>
</div>