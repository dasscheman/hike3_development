<?php
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<!-- <div class="col-sm-4"> -->
        <div class="well">

        <p>
        <?php
        Pjax::begin(['id' => 'open-vragen-antwoorden-list-' . $model->open_vragen_antwoorden_ID, 'enablePushState' => false]);
        echo AlertBlock::widget([
            'type' => AlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => 4000,
        ]);
        ?>
        </p>
        <h3>
        <?php echo Html::encode($model->openVragen->open_vragen_name); ?>
        </h3>
        <i>
        <?php echo Html::encode($model->getGroup()->one()->group_name); ?>
        </i>
        <br>

        <?php
        if (!$model->checked) {
            echo Html::a(
                Yii::t('app', 'Correct awnser'),
                ['/open-vragen-antwoorden/antwoord-goed', 'open_vragen_antwoorden_ID' => $model->open_vragen_antwoorden_ID],
                [
                    'class' => 'btn btn-xs btn-success',
                    'id' => 'correct-awnser-' . $model->open_vragen_antwoorden_ID
                ],
                ['data-pjax' => 'open-vragen-antwoorden-list-' . $model->open_vragen_antwoorden_ID]
            );
            echo Html::a(
                Yii::t('app', 'Wrong awnser'),
                ['/open-vragen-antwoorden/antwoord-fout', 'open_vragen_antwoorden_ID' => $model->open_vragen_antwoorden_ID],
                [
                    'class' => 'btn btn-xs btn-danger',
                    'id' => 'wrong-awnser-' . $model->open_vragen_antwoorden_ID
                ],
                ['data-pjax' => 'open-vragen-antwoorden-list-' . $model->open_vragen_antwoorden_ID]
            );
        }
        ?>
        </br>
        <b>
        <?php echo Html::encode($model->openVragen->getAttributeLabel('omschrijving')); ?>:
        </b>
        <?php echo Html::encode($model->openVragen->omschrijving); ?></br>
        <b>
        <?php echo Html::encode($model->openVragen->getAttributeLabel('vraag')); ?>:
        </b>
        <?php echo Html::encode($model->openVragen->vraag); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('antwoord_spelers')); ?>:
        </b>
        <?php echo Html::encode($model->antwoord_spelers); ?></br>
        <b>
        <?php echo Html::encode($model->openVragen->getAttributeLabel('score')); ?>:
        </b>
        <?php echo Html::encode($model->openVragen->score); ?></br>
        <b>
        <?php echo Html::encode($model->openVragen->getAttributeLabel('goede_antwoord')); ?>:
        </b>
        <?php echo Html::encode($model->openVragen->goede_antwoord); ?></br>

        <?php Pjax::end(); ?>

        </div>
<!-- </div> -->
