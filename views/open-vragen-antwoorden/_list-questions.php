<?php
use prawee\widgets\ButtonAjax;
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\DeelnemersEvent;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <?php
    $group_ID = DeelnemersEvent::getGroupOfPlayer(Yii::$app->user->identity->selected, Yii::$app->user->id);
    ?>
    <h4>
        <?php echo Html::encode($model->open_vragen_name); ?>
    </h4>
    <?php
        echo ButtonAjax::widget([
            'name' => Yii::t('app', 'Awnser question'),
            'route' => [
                '/open-vragen-antwoorden/beantwoorden',
                'open_vragen_ID' => $model->open_vragen_ID,
                'group_ID' => $group_ID
            ],
            'modalId'=>'#main-modal',
            'modalContent'=>'#main-content-modal',
            'options' => [
                'class' => 'btn btn-xs btn-success',
                'title' => Yii::t('app', 'Open Hint'),
                'disabled' => !Yii::$app->user->identity->isActionAllowed(
                    'open-vragen-antwoorden',
                    'beantwoorden',
                    [
                        'open_vragen_ID' => $model->open_vragen_ID,
                        'group_ID' => $group_ID
                    ]),
            ]
        ]);
    ?>
    </br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('omschrijving')); ?>:
    </b>
    <?php echo Html::encode($model->omschrijving); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('vraag')); ?>:
    </b>
    <?php echo Html::encode($model->vraag); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
    </b>
    <?php echo Html::encode($model->score); ?></br>
</div>
