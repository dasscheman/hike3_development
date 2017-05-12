<?php
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <?php
    Pjax::begin(['id' => 'open-vragen-antwoorden-list-dashboard-' . $model->open_vragen_ID, 'enablePushState' => false]);
    echo AlertBlock::widget([
        'type' => AlertBlock::TYPE_ALERT,
        'useSessionFlash' => true,
        'delay' => 4000,
    ]);
    ?>
    </p>
    <h4>
        <?php echo Html::encode($model->open_vragen_name); ?>
    </h4>
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

    <?php
    echo Html::a(
        Yii::t('app', 'Awnser question'),
        ['/open-vragen-antwoorden/create', 'open_vragen_ID' => $model->open_vragen_ID],
        [
            'class' => 'btn btn-xs btn-success',
            'data-pjax' => 'open-vragen-antwoorden-list-' . $model->open_vragen_ID
        ]
    );
    Pjax::end(); ?>
</div>
