<?php
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */
?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">

        <p>
        <?php
        Pjax::begin(['id' => 'open-vragen-antwoorden-list-' . $model->open_vragen_ID, 'enablePushState' => false]);
        echo AlertBlock::widget([
            'type' => AlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => 4000,
        ]);
        ?>
        </p>
        <h3>
        <?php echo Html::encode($model->open_vragen_name); ?>
        </h3>
        <?php
        if (!$model->isQuestionAwnseredByGroup()) {
            echo Html::a(
                Yii::t('app', 'Awnser question'),
                ['/open-vragen-antwoorden/create', 'id' => $model->open_vragen_ID],
                [
                    'class' => 'btn btn-xs btn-success',
                    'data-pjax' => 'open-vragen-antwoorden-list-' . $model->open_vragen_ID
                ]
            );?>
            <br>
        <?php } ?>
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

        <?php if ($model->isQuestionAwnseredByGroup()) { ?>
            <b>
            <?php echo Html::encode($model->getAwnserByGroup()->getAttributeLabel('antwoord_spelers')); ?>:
            </b>
            <?php echo Html::encode($model->getAwnserByGroup()->antwoord_spelers); ?></br>
            <b>
            <?php echo Html::encode($model->getAwnserByGroup()->getAttributeLabel('checked')); ?>:
            </b>
            <?php echo GeneralFunctions::printGlyphiconCheck($model->getAwnserByGroup()->checked); ?>
            <b>
            <?php echo Html::encode($model->getAwnserByGroup()->getAttributeLabel('correct')); ?>:
            </b>
            <?php echo GeneralFunctions::printGlyphiconCheck($model->getAwnserByGroup()->correct);
        }?></br>

        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
