<?php

use app\models\Users;
use kartik\builder\Form;
use kartik\detail\DetailView;
use kartik\widgets\ActiveForm;
use prawee\widgets\ButtonAjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use app\models\Groups;
use kartik\editable\Editable;
use app\models\EventNames;
use app\models\Bonuspunten;
use app\models\PostPassage;
use kartik\time\TimePicker;
use kartik\datetime\DateTimePicker;
use app\models\DeelnemersEvent;
use app\components\CustomAlertBlock;

use kartik\widgets\Select2;
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Hike overview');
?>

<div class="site-index-organisatie">
    <div class="container text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-sm-3 well">
                <?php
                echo CustomAlertBlock::widget([
                    'type' => CustomAlertBlock::TYPE_ALERT,
                    'useSessionFlash' => true,
                    'delay' => FALSE,
                ]);
                ?>
                <div class="well">
                    <h3><?php echo $eventModel->event_name ?></h3>
                    <?php
                    if (is_file(Url::to(Yii::$app->params['event_images_path'] . $eventModel->image))) {
                        $image = Url::to('@web/uploads/event_images/' . $eventModel->image);
                    } else {
                        $image = Url::to('@web/images/kiwilogo.jpg');
                    }
                    echo Html::img($image, ['class' => 'img-circle', 'height'=>"65", 'width'=>"65"]);?>
                    </br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('organisatie')); ?>:
                    </b>
                    <?php echo Html::encode($eventModel->organisatie); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('website')); ?>:
                    </b>
                    <?php echo Html::encode($eventModel->website); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('start_date')); ?>:
                    </b>
                    <?php echo Html::encode($eventModel->start_date); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('end_date')); ?>:
                    </b>
                    <?php echo Html::encode($eventModel->end_date); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('status')); ?>:
                    </b>
                    <?php echo Editable::widget([
                            'model'=> $eventModel,
                            'attribute' => 'status',
                            'formOptions' => [
                                'action' => Url::to(['/event-names/change-status']),
                            ],
                            'buttonsTemplate' => '{submit}',
                            'submitButton' => [
                                'icon' => '<i class="glyphicon glyphicon-floppy-disk"></i>',
                                'class' => 'btn btn-sm btn-primary',
                                'label' => Yii::t('app', 'Save'),
                                'id' => $eventModel->event_ID.'-is_active_status-submit'
                            ],
                            // Er word hier een redirect gedaan na de submit. Die geeft een ajax error.
                            // 302 als standaard, en als 200 wordt gebruikt ook. Iets met JSON format.
                            // Omdat de DB fouten afgevangen worden, wordt de ajax errors onderdrukt.
                            'showAjaxErrors' => FALSE,
                            'asPopover' => TRUE,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => $eventModel->getStatusOptions(),
                            'options' => [
                                'class'=>'form-control',
                                'id' => $eventModel->event_ID.'-is_active_status'
                            ],
                            'displayValue' => $eventModel->getStatusText(),
                        ]); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('active_day')); ?>:
                    </b>
                    <?php echo  Editable::widget([
                            'name'=>'active_day',
                            'model'=> $eventModel,
                            'attribute' => 'active_day',
                            'formOptions' => [
                                'action' => Url::to(['/event-names/change-day']),
                            ],
                            'buttonsTemplate' => '{submit}',
                            'submitButton' => [
                                'icon' => '<i class="glyphicon glyphicon-floppy-disk"></i>',
                                'class' => 'btn btn-sm btn-primary',
                                'label' => Yii::t('app', 'Save'),
                                'id' => $eventModel->event_ID.'-is_active_day-submit'
                            ],
                            // Er word hier een redirect gedaan na de submit. Die geeft een ajax error.
                            // 302 als standaard, en als 200 wordt gebruikt ook. Iets met JSON format.
                            // Omdat de DB fouten afgevangen worden, wordt de ajax errors onderdrukt.
                            'showAjaxErrors' => FALSE,
                            'asPopover' => TRUE,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => $eventModel->getDatesAvailable(FALSE),
                            'options' =>
                            [
                                'id' => $eventModel->event_ID.'-is_active_day',
                            ],
                            'disabled' => $eventModel->status === EventNames::STATUS_gestart ? FALSE : TRUE,
                            'displayValue' => $eventModel->status === EventNames::STATUS_gestart ? $eventModel->active_day : Yii::t('app', 'na'),
                        ]); ?></br>
                    <b>
                    <?php echo Html::encode($eventModel->getAttributeLabel('max_time')); ?>:
                    </b>
                    <?php echo Html::encode((empty($eventModel->max_time)) ? '(not set)' : $eventModel->max_time); ?></br>
              </div>
              <div class="well">
                <h3><?php echo Yii::t('app', 'actions')?></h3>
                <?php
                if ($eventModel->status === EventNames::STATUS_opstart) { ?>
                    <p>
                        <?php
                        echo Html::a(
                                Yii::t('app', 'Add route items'),
                                ['/route/index'],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                ]);
                        ?>
                    </p>
                    <p>
                        <?php
                        echo Html::a(
                                Yii::t('app', 'Add stations'),
                                ['/posten/index'],
                                [
                                    'class' => 'btn btn-xs btn-success',
                                ]);
                        ?>
                    </p> <?php
                }
                ?>
                <p>
                    <?php
                    Modal::begin([
                        'toggleButton' => [
                            'label' => Yii::t('app', 'Change settings hike'),
                            'id' => 'modalChangeSettingsButton',
                            'class' => 'btn btn-xs btn-success',
                            'disabled' => !Yii::$app->user->identity->isActionAllowed(
                                'event-names',
                                'update',
                                [
                                    'event_ID' => $eventModel->event_ID,
                                    'action' => 'change_settings'
                                ]),
                        ],
                    ]);
                    ?>
                </p>
                <p>
                    <?php

                    echo $this->render('/event-names/update', [
                        'model' => $eventModel,
                        'action' => 'change_settings']);
                    Modal::end();

                    // TimePicker within a bootstrap modal window with initial values.
                    Modal::begin([
                    	'toggleButton' => [
                            'label' => Yii::t('app', 'Change max time hike'),
                            'id' => 'modalChangeMaxTimeButton',
                            'class' => 'btn btn-xs btn-success',
                            'disabled' => !Yii::$app->user->identity->isActionAllowed(
                                'event-names',
                                'update',
                                [
                                    'event_ID' => $eventModel->event_ID,
                                    'action' => 'set_max_time'
                                ]),
                        ],
                    ]);
                    echo $this->render('/event-names/update', [
                        'model' => $eventModel,
                        'action' => 'set_max_time']);
                    Modal::end();

                    ?>
                </p>
                <p>
                    <?php
                    echo ButtonAjax::widget([
                        'name' => Yii::t('app', 'Assign bonuspoints'),
                        'route' => [
                            '/bonuspunten/create',
                        ],
                        'modalId'=>'#main-modal',
                        'modalContent'=>'#main-content-modal',
                        'options' => [
                            'class' => 'btn btn-xs btn-success',
                            'title' => Yii::t('app', 'Assign bonuspoints'),
                            'disabled' => !Yii::$app->user->identity->isActionAllowed(
                                'bonuspunten',
                                'create'
                            ),
                        ]
                    ]);
                    ?>
                </p>
              </div>

              <p>
                  <?php
                      echo ListView::widget([
                          'summary' => FALSE,
                          'pager' => FALSE,
                          'dataProvider' => $dataProviderCheck,
                          'itemView' => '/open-vragen-antwoorden/_list-controle',
                          'emptyText' => Yii::t('app', 'No question which should be checked'),
                      ]);
                  ?>
              </p>
              <p>
              <?php

              Modal::begin(
                 [
                     'options' => [
                         'tabindex' => true // important for Select2 to work properly
                     ],
                     'toggleButton' => [
                         'label' => Yii::t('app', 'Add organizer to hike'),
                         'id' => 'modalAddOrganisationButton',
                         'class' => 'btn btn-xs btn-success',
                         'disabled' => !Yii::$app->user->identity->isActionAllowed('deelnemers-event', 'create'),
                     ],
                 ]
              );

              echo $this->render('/deelnemers-event/create', ['model' => $modelDeelnemer]);
              Modal::end();?>
              </p>
              <?php
              echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => FALSE,
                  'dataProvider' => $organisatieData,
                  'itemView' => '/deelnemers-event/_list',
                  'emptyText' => Yii::t('app', 'No organisation.'),
              ]); ?>
              <p>


            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        Modal::begin(['id'=>'main-modal']);
                        echo '<div id="main-content-modal"></div>';
                        Modal::end();
                        ?>
                    </div>
                </div>
                <?php
              echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => [
                      'prevPageLabel' => Yii::t('app', 'previous'),
                      'nextPageLabel' => Yii::t('app', 'next'),
                      'maxButtonCount' => 5,
                      'options' => [
                         'class' => 'pagination pagination-sm',
                      ],
                  ],
                  'dataProvider' => $activityFeed,
                  'itemView' => '/groups/_list-feed',
                  'emptyText' => Yii::t('app', 'No feeds activity for this hike.'),
              ]);
              ?>
            </div>

            <div class="col-sm-3 well">
              <div class="thumbnail">
                <?php
                $form = ActiveForm::begin([
                    'options'=>['enctype'=>'multipart/form-data'],
                    'action' => ['event-names/upload','event_ID' => $eventModel->event_ID],// important
                ]); ?>
                <p>
                <?php
                // your fileinput widget for single file upload
                echo $form->field($eventModel, 'image_temp')->widget(FileInput::classname(), [
                    'options'=>['accept'=>'image/*'],
                    'disabled' => !Yii::$app->user->identity->isActionAllowed('event-names', 'upload', ['event_ID' => $eventModel->event_ID]),
                    'pluginOptions'=>[
                        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif','png'],
                        'uploadLabel' => Yii::t('app',  'save'),
                        'removeLabel' => '',
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' => '',
            ]
                ]);
                ActiveForm::end();
                ?>
                </p>
              </div>
              <?php
              Modal::begin(
                  [
                      'id' =>'modalCreateGroup',
                      'toggleButton' => [
                          'label' => Yii::t('app', 'Add runner group to hike'),
                          'class' => 'btn btn-xs btn-success',
                          'disabled' => !Yii::$app->user->identity->isActionAllowed('groups', 'create'),
                      ],
                  ]
              );

              echo $this->render('/groups/create', ['model' => $groupModel]);
              Modal::end();
              ?>
              </p>

              <?php
              echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => FALSE,
                  'dataProvider' => $groupsData,
                  'itemView' => '/groups/_list',
                  'emptyText' => Yii::t('app', 'No groups added to the hike.'),
              ]);
              ?>
            </div>
          </div>
        </div>
    </div>
</div>
