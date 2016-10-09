<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use app\models\FriendList;
/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-deelnemers-event-form">

    <?php $form = ActiveForm::begin(); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php //echo $form->labelEx($model,'user_ID'); ?>
		<?php //echo $form->hiddenField($model, 'user_ID'); ?>
        
        <?php echo AutoComplete::widget([
            'model' => $model,
            'attribute' => 'user_ID',
            'clientOptions' => [
                'source' => FriendList::getFriendNames(),
            ],
        ]);

//        <?php AutoComplete::widget('zii.widgets.jui.CJuiAutoComplete', array(
//					'name'=>'user_ID',
//					'value'=>Users::model()->getUserName($model->user_ID),
//					'source'=>FriendList::model()->getFriendNameOptions(),
//					'options'=>array(
//						'minLength'=>'1',
//						'select'=>"js:function(event, ui) {
//                                          $('#DeelnemersEvent_user_ID').val(ui.item.id);
//                                        }"
//					),
//				)); ?>
		<?php echo $form->error($model,'user_ID'); ?>
	</div>

	<div class="row">
		<?php //echo $form->labelEx($model,'rol'); ?>
		<?php //echo $form->textField($model,'rol'); 
              echo $form->dropDownList(
                $model, 
                'rol', 
                DeelnemersEvent::model()->getRolOptions(),
                array(
                    'ajax' => array(
                        'url' => CController::createUrl('dynamicRol'),
                        'type' => 'POST',                     
                        'update'=>'#DeelnemersEvent_group_ID',
                        'data'=>array('rol'=>'js:this.value',
								      'event_id'=>$_GET['event_id'])),
                    'empty' => '--Selecteer een Rol--', 'style'=>'width:220px;')); 
              //echo $form->dropDownList($model,'rol', DeelnemersEvent::model()->getRolOptions());?>
		<?php echo $form->error($model,'rol'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_ID'); ?>
		<?php //echo $form->textField($model,'group_ID'); 
              echo CHtml::dropDownList('DeelnemersEvent[group_ID]',"", array(), array('prompt'=> '--Selecteer eerst een rol--', 'style'=>'width:220px;'));
              //echo $form->dropDownList($model,'group_ID', Groups::model()->getGroupOptions());?>
		<?php echo $form->error($model,'group_ID'); ?>
	</div>
<!--
	<div class="row">
		<?php /*echo $form->labelEx($model,'create_time'); ?>
		<?php echo $form->textField($model,'create_time'); ?>
		<?php echo $form->error($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_user_ID'); ?>
		<?php echo $form->textField($model,'create_user_ID'); ?>
		<?php echo $form->error($model,'create_user_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php echo $form->textField($model,'update_time'); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_user_ID'); ?>
		<?php echo $form->textField($model,'update_user_ID'); ?>
		<?php echo $form->error($model,'update_user_ID'); */?>
	</div>
-->
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php ActiveForm::end(); ?>

</div><!-- form -->