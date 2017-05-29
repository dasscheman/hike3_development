<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;

/* @var $this GroupsController */
/* @var $data Groups */

?>


<div class="well">
    <?php
        echo ButtonAjax::widget([
            'name' => $model->user->voornaam . ' ' . $model->user->achternaam,
            'route' => [
                'deelnemers-event/update',
                'deelnemers_ID'=>$model->deelnemers_ID,
            ],
            'modalId'=>'#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-link',
                'title' => Yii::t('app', 'Edit player'),
                'disabled' => !Yii::$app->user->identity->isActionAllowed('deelnemers-event', 'update', ['deelnemers_ID' => $model->deelnemers_ID]),
            ]
        ]);
    ?>
    <br/>
    <?php echo Html::encode($model->getRolTextObj()); ?>
</div>
