<?php
use app\components\GeneralFunctions;
use yii\helpers\Html;
use kartik\widgets\AlertBlock;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>

<div class="col-sm-3">
    <div class="row-1">
        <div class="view">

        <p>
        <?php
        Pjax::begin(['id' => 'post-passage-list-' . $model->posten_passage_ID, 'enablePushState' => false]);
        echo AlertBlock::widget([
            'type' => AlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => 4000,

        ]);
        ?>

        </p>
        <h3>
        <?php echo Html::encode($model->post->post_name); ?>
        </h3>
        <?php
        if (Yii::$app->user->identity->isActionAllowed('post-passage', 'update', ['posten_passage_ID' => $model->posten_passage_ID])) {
            echo Html::a(
                Yii::t('app', 'Edit station checkin'),
                ['/post-passage/update', 'id' => $model->posten_passage_ID],
                ['class' => 'btn btn-xs btn-success'],
                ['data-pjax' => 'post-passage-list-' . $model->posten_passage_ID]
            );
        } ?>
        </br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('gepasseerd')); ?>
        </b>
        <?php echo GeneralFunctions::printGlyphiconCheck($model->gepasseerd); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('binnenkomst')); ?>
        </b>
        <?php echo Html::encode($model->binnenkomst); ?></br>
        <b>
        <?php echo Html::encode($model->getAttributeLabel('vertrek')); ?>
        </b>
        <?php echo Html::encode($model->vertrek); ?></br>

        <?php Pjax::end(); ?>
        </div>
    </div>
</div>
