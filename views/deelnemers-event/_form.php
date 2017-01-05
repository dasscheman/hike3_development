<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\FriendList;
use kartik\widgets\Select2;

use app\models\DeelnemersEvent;
/* @var $this yii\web\View */
/* @var $model app\models\DeelnemersEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbl-deelnemers-event-form">
    <?php $form = ActiveForm::begin([
        'action' => $model->isNewRecord ? ['deelnemers-event/create'] : ['deelnemers-event/update',
        'id' => $model->deelnemers_ID]]); ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'user_ID')->widget(Select2::classname(), [
                'data' => FriendList::getFriendsForEvent(),
                'options' => ['placeholder' => 'Filter as you type ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'id' => 'add-user-' . $model->group_ID,
                ]
            ]);   
        }
        
        echo $form->field($model, 'rol')->dropDownList(DeelnemersEvent::getOrganisationRolOptions()); 
        
        ?>
        <div class="form-deelnemers-event">

            <?= Html::submitButton(
                $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'value'=>'create',
                    'name'=>'submit']) ?>
            <?php
            if (!$model->isNewRecord) {
                echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'submit']);
            } ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>