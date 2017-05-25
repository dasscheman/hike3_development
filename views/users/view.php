<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\EventNames;
use kartik\widgets\AlertBlock;
use yii\widgets\ListView;
use geertw\Yii2\Adsense;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = Yii::t('app', 'Overview') . ' '. $model->username;

?>
<div class="container text-center">
  <div class="row">
    <div class="col-sm-3 well">
      <div class="well">
          <h3><?php echo  $model->username ?></h3>
          <?php echo '(' . Html::encode($model->email) . ')'; ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('voornaam')); ?>:
          </b>
          <?php echo Html::encode($model->voornaam); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('achternaam')); ?>:
          </b>
          <?php echo Html::encode($model->achternaam); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('organisatie')); ?>:
          </b>
          <?php echo Html::encode($model->organisatie); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('birthdate')); ?>:
          </b>
          <?php echo Html::encode($model->birthdate); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('last_login_time')); ?>:
          </b>
          <?php echo Html::encode($model->last_login_time); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('selected')); ?>:
          </b>
          <?php echo Html::encode(EventNames::getEventName(Yii::$app->user->identity->selected)); ?></br>

      </div>
      <div class="well">
        <h3><?php echo Yii::t('app', 'actions')?></h3>
        <p>
            <?php
            Modal::begin(
            [
                'toggleButton' => [
                    'label' => Yii::t('app', 'Edit settings user'),
                    'class' => 'btn  btn-xs btn-success'
                ],
                'closeButton' => [
                    'label' => 'Close',
                    'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => Modal::SIZE_LARGE,
            ]);

            echo Yii::$app->controller->renderPartial('/users/update', ['model' => $model]);
            Modal::end();

            Modal::begin(
            [
                'toggleButton' => [
                    'label' => Yii::t('app', 'Change password'),
                    'class' => 'btn  btn-xs btn-success'
                ],
                'closeButton' => [
                    'label' => 'Close',
                    'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => Modal::SIZE_LARGE,
            ]);
            echo $this->render('_change-password', ['model' => $model]);
            Modal::end();
            ?>
        </p>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
            <?php
                echo AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => 60000,

                ]);
                echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => [
                      'prevPageLabel' => Yii::t('app', 'previous'),
                      'nextPageLabel' => Yii::t('app', 'next'),
                      'maxButtonCount' => 3,
                      'options' => [
                         'class' => 'pagination pagination-sm',
                      ],
                  ],
                  'dataProvider' => $activityFeed,
                  'itemView' => '/users/_list-feed',
                  'emptyText' => 'Er is geen activiteit voor dit profiel.',
                ]);
            ?>
        </div>
      </div>
    </div>
    <div class="col-sm-3 well">

        <div class="well">
        </div>

        <div class="well">

        </div>
        <?php echo Yii::$app->controller->renderPartial('/friendlist/view-dashboard', ['model' => $friendRequestData]); ?>
    </div>
  </div>
</div>
