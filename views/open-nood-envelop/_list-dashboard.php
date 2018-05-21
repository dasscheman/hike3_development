<?php
use prawee\widgets\ButtonAjax;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\AlertBlock;
use kartik\widgets\Alert;
use app\models\DeelnemersEvent;

/* @var $this GroupsController */
/* @var $data Groups */

?>
    <div class="view">
    <h4>
        <?php echo Html::encode($model->nood_envelop_name); ?>
    </h4>
    <?php
    if (!$model->isHintOpenedByGroup()) {
        $group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected_event_ID, Yii::$app->user->id);

        echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Open Hint'),
            'route' => [
                '/open-nood-envelop/open',
                'nood_envelop_ID'=>$model->nood_envelop_ID,
                'group_ID' => $group_ID
            ],
            'modalId'=>'#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-xs btn-success',
                'title' => Yii::t('app', 'Open Hint'),
                'disabled' => !Yii::$app->user->can('deelnemerIntroductie') && !Yii::$app->user->can('deelnemerGestartTime')
            ]
        ]);
    }
    ?>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
    </b>
    <?php echo Html::encode($model->score); ?></br>

    <?php
    if ($model->isHintOpenedByGroup()) {
        if ($model->show_coordinates) {
            ?>
            <b>
            <?php
            echo Html::encode($model->getAttributeLabel('coordinaat')); ?>:
            </b>
            <?php echo Html::encode($model->coordinaat); ?> </br>
        <?php
        } ?>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('opmerkingen')); ?>:
        </b>
        <?php echo Html::encode($model->opmerkingen); ?></br>
        <b>
        <?php echo Html::encode($model->getOpenNoodEnvelops()->one()->getAttributeLabel('create_user_ID')); ?>:
        </b>
        <?php echo Html::encode($model->getOpenNoodEnvelops()->one()->createUser->voornaam . ' ' . $model->getOpenNoodEnvelops()->one()->createUser->achternaam); ?></br>
        <b>
        <?php echo Html::encode($model->getOpenNoodEnvelops()->one()->getAttributeLabel('create_time')); ?>:
        </b>
        <?php echo Html::encode($model->getOpenNoodEnvelops()->one()->create_time);
    }?>
    </div>
