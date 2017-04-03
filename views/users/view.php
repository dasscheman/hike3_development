<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\EventNames;
use kartik\widgets\AlertBlock;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$bordered = TRUE;
$striped = TRUE;
$condensed = TRUE;
$responsive = FALSE;
$hover = FALSE;
$hAlign = DetailView::ALIGN_RIGHT;
$vAlign = DetailView::ALIGN_TOP;
$fadeDelay = TRUE;

$attributes = [
    [
        'columns' => [
            [
                'attribute' => 'username',
                'displayOnly' => true,
                'valueColOptions' => ['style' => 'width:30%']
            ],
            [
                'attribute' => 'email',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
                'displayOnly' => true
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'voornaam',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'achternaam',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'organisatie',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'birthdate',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
        ],
    ],
    [
        'columns' => [
            [
                'attribute' => 'last_login_time',
                'valueColOptions' => ['style' => 'width:30%'],
            ],
            [
                'attribute' => 'selected',
                'format' => 'raw',
                'valueColOptions' => ['style' => 'width:30%'],
                'value' => EventNames::getEventName(Yii::$app->user->identity->selected),
            ],
        ],
    ],
];







$this->title = Yii::t('app', 'Overview') . ' '. $model->username;

?>
<div class="container text-center">
  <div class="row">
    <div class="col-sm-3 well">
      <div class="well">
          <?php echo $model->username ?>
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
            echo $this->render('_form', ['model' => $model]);
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
    <div class="col-sm-7">

      <div class="row">
        <div class="col-sm-12">
          <div class="panel panel-default text-left">
            <div class="panel-body">
                <?= AlertBlock::widget([
                    'type' => AlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => 60000,

                ]); ?>
              <p contenteditable="true">          <?php
                    // View file rendering the widget
                    echo DetailView::widget([
                        'model' => $model,
                        'attributes' => $attributes,
                        'mode' => 'view',
                        'bordered' => $bordered,
                        'striped' => $striped,
                        'condensed' => $condensed,
                        'responsive' => $responsive,
                        'hover' => $hover,
                        'hAlign'=>$hAlign,
                        'vAlign'=>$vAlign,
                        'fadeDelay'=>$fadeDelay,
                        'deleteOptions' => [ // your ajax delete parameters
                            'params' => ['id' => 1000, 'kvdelete' => true],
                        ],
                        'container' => ['id' => 'kv-demo'],
                        'formOptions' => ['action' => Url::current(['#' => 'kv-demo'])] // your action to delete
                    ]);
                ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="row">
        <div class="col-sm-3">
          <div class="well">
           <p>John</p>
           <img src="bird.jpg" class="img-circle" height="55" width="55" alt="Avatar">
          </div>
        </div>
        <div class="col-sm-9">
          <div class="well">
            <p>Just Forgot that I had to mention something about someone to someone about how I forgot something, but now I forgot it. Ahh, forget it! Or wait. I remember.... no I don't.</p>
          </div>
        </div>
      </div> -->

    </div>
    <div class="col-sm-2 well">
      <div class="thumbnail">
        <p>Upcoming Events:</p>
        <img src="paris.jpg" alt="Paris" width="400" height="300">
        <p><strong>Paris</strong></p>
        <p>Fri. 27 November 2015</p>
        <button class="btn btn-primary">Info</button>
      </div>
      <div class="well">
        <p>ADS</p>
      </div>
      <div class="well">
        <p>ADS</p>
      </div>
    </div>
  </div>
</div>
