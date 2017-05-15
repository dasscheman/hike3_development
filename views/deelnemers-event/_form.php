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
        'deelnemers_ID' => $model->deelnemers_ID]]);

        if ($model->isNewRecord) {
            echo $form->field($model, 'user_ID')->widget(Select2::classname(), [
                'data' => FriendList::getFriendsForEvent(),
                'options' => ['placeholder' => 'Filter as you type ...'],
                'pluginOptions' => [
                ]
            ]);
        }

        echo $form->field(
                $model,
                'rol',
                [
                    'options' =>
                    [
                        'id' => $model->isNewRecord ?
                            'deelnemers-event-rol-field-create' :
                            'deelnemers-event-rol-field-update-' . $model->user_ID
                    ]
                ])
            ->dropDownList(
                DeelnemersEvent::getOrganisationRolOptions(),
                [
                    'id' => $model->isNewRecord ?
                        'deelnemers-event-rol-dropdown-create' :
                        'deelnemers-event-rol-dropdown-update-' . $model->user_ID
                ]);

        ?>
        <div class="form-deelnemers-event">
            <?= Html::submitButton(
                $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
                [
                    'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
                    'id'=> $model->isNewRecord ?
                        'deelnemers-event-form-create' :
                        'deelnemers-event-form-update-' . $model->user_ID,
                    'value'=> $model->isNewRecord ? 'create' : 'update',
                    'name'=>'action'
                ]) ?>
            <?php
            if (!$model->isNewRecord) {
                echo Html::submitButton(Yii::t('app', 'Delete'), ['class' => 'btn btn-delete', 'value'=>'delete', 'name'=>'action']);
            } ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
