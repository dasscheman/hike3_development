<?php
use yii\helpers\Html;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
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
                    Pjax::begin([
                        'id' => 'bonuspunten-list-' . $model->bouspunten_ID,
                        'enablePushState' => false
                    ]);
                    echo AlertBlock::widget([
                        'type' => AlertBlock::TYPE_ALERT,
                        'useSessionFlash' => true,
                        'delay' => 4000,

                    ]);
                ?>
                <h3>
                    <?php echo Html::encode($model->omschrijving); ?>
                </h3>
                <?php
                if (Yii::$app->user->can('organisatie')) {
                    echo ButtonAjax::widget([
                       'name' => Yii::t('app', 'Edit bonuspunten'),
                       'route' => ['/bonuspunten/update', 'bonuspunten_ID' => $model->bouspunten_ID],
                       'modalId' => '#main-modal',
                       'modalContent'=>'#main-content-modal',
                       'options' => [
                           'class' => 'btn btn-xs btn-success',
                           'title' => Yii::t('app', 'Edit bonuspunten'),
                           'disabled' => !Yii::$app->user->can('organisatie'),
                       ]
                   ]);
                }
                ?>
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
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>
