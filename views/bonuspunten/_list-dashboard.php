<?php
use app\components\GeneralFunctions;
use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this GroupsController */
/* @var $data Groups */

?>
<div class="view">
    <p>
        <?php echo Html::encode($model->omschrijving); ?>
    </p>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('score')); ?>:
    </b>
    <?php echo Html::encode($model->score); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('create_user_ID')); ?>:
    </b>
    <?php echo Html::encode($model->createUser->voornaam . ' ' . $model->createUser->achternaam); ?></br>
    <b>
    <?php echo Html::encode($model->getAttributeLabel('create_time')); ?>:
    </b>
    <?php echo Html::encode($model->create_time); ?></br>

</div>
