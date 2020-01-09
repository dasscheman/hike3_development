<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\models\EventNames;
use kartik\widgets\AlertBlock;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
$eventNames = new EventNames;

$this->title = Yii::t('app', 'Overview') . ' '. $model->voornaam . ' ' . $model->achternaam;

?>
<div class="container text-center">
  <div class="row">
    <div class="col-sm-3 well">
      <div class="well">
          <?php
              Modal::begin(
              [
                  'toggleButton' => [
                      'label' => $model->voornaam . ' ' . $model->achternaam,
                      'class' => 'btn btn-lg btn-link'
                  ],
                  'closeButton' => [
                      'label' => Yii::t('app', 'Close'),
                      'class' => 'btn btn-danger btn-sm pull-right',
                  ],
                  'size' => Modal::SIZE_LARGE,
              ]
);

              Pjax::begin([
                  'id' => 'users-update-form',
                  'enablePushState' => false,
              ]);
              echo Yii::$app->controller->renderPartial('/users/update', ['model' => $model]);
              Pjax::end();
              Modal::end();
              ?>

          <?php echo Html::encode($model->email); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('organisatie')); ?>:
          </b>
          <?php echo Html::encode($model->organisatie); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('birthdate')); ?>:
          </b>
          <?php echo Html::encode($model->birthdate); ?></br>
          <b>
          <?php echo Html::encode($model->getAttributeLabel('selected')); ?>:
          </b>
          <?php echo Html::encode($eventNames->getEventName(Yii::$app->user->identity->selected_event_ID)); ?></br>
          <?php
                Modal::begin(
                [
                  'toggleButton' => [
                      'label' => Yii::t('app', 'Change password'),
                      'class' => 'btn  btn-xs btn-link'
                  ],
                  'closeButton' => [
                      'label' => 'Close',
                      'class' => 'btn btn-danger btn-sm pull-right',
                  ],
                  'size' => Modal::SIZE_LARGE,
                ]
              );
                echo $this->render('_change-password', ['model' => $model]);
                Modal::end();
          ?>

      </div>
      <div class="well">
      <?php
      Modal::begin(
      [
        'toggleButton' => [
            'label' => Yii::t('app', 'Search friends'),
            'class' => 'btn btn-md btn-success'
        ],
        'closeButton' => [
            'label' => 'Close',
            'class' => 'btn btn-danger btn-sm pull-right',
        ],
        'size' => Modal::SIZE_LARGE,
      ]
          );
      echo $this->render('search-new-friends', ['searchModel' => $searchModel, 'dataProvider'=>$dataProvider]);
      Modal::end();

      ?>

      </div>
    </div>
    <div class="col-sm-6">
      <div class="row">
        <div class="col-sm-12">
            <?php
                echo AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => false,

                ]);
                echo ListView::widget([
                  'summary' => false,
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
        <?php

            Modal::begin(
            [
                'toggleButton' => [
                    'label' => Yii::t('app', 'Create new hike'),
                    'class' => 'btn btn-md btn-success'
                ],
                'closeButton' => [
                    'label' => Yii::t('app', 'Close'),
                    'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => Modal::SIZE_LARGE,
            ]
            );
            Pjax::begin([
                'id' => 'event-names-create-form',
                'enablePushState' => false,
            ]);
            echo $this->render('/event-names/create', [
                'model' => new EventNames([
                    'start_date' => date('d-m-Y'),
                    'end_date' => date('d-m-Y')]),
                'action' => 'create'
            ]);
            Pjax::end();
            Modal::end();
        ?>
        </div>

        <div class="well">

        </div>
        <?php echo Yii::$app->controller->renderPartial('/friendlist/view-dashboard', ['model' => $friendRequestData]); ?>
    </div>
  </div>
</div>
