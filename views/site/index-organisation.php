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
    use kartik\widgets\AlertBlock;

    $bordered = TRUE;
    $striped = TRUE;
    $condensed = TRUE;
    $responsive = FALSE;
    $hover = FALSE;
    $hAlign = DetailView::ALIGN_RIGHT;
    $vAlign = DetailView::ALIGN_TOP;
    $fadeDelay = TRUE;

    /* @var $this yii\web\View */
    $this->title = Yii::t('app', 'Hike overview');

    ?>
    <div class="organisatie-overview">
        <div class="container text-center">
          <div class="row">
            <div class="col-sm-3 well">
              <div class="well">
                <h3><?php echo $eventModel->event_name ?></h3>
                <?php echo Html::img('@web/uploads/event_images/' . $eventModel->image, ['class' => 'img-circle', 'height'=>"65", 'width'=>"65"]);?>
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
                        'data' => $eventModel->getDatesAvailable($eventModel->event_ID),
                        'options' =>
                        [
                            'id' => $eventModel->event_ID.'-is_active_day',
        //                            'class'=>'form-control',
                        ],
                        'disabled' => $eventModel->status === EventNames::STATUS_gestart ? FALSE : TRUE,
                        'displayValue' => $eventModel->status === EventNames::STATUS_gestart ? $eventModel->active_day : Yii::t('app', 'na'),
                    ]); ?></br>
              </div>

              <?php
              echo AlertBlock::widget([
                  'type' => AlertBlock::TYPE_ALERT,
                  'useSessionFlash' => true,
                  'delay' => 60000,
              ]); ?>
            <!-- </div> -->
              <div class="well" style="overflow: auto;">
                <h3><?php echo Yii::t('app', 'actions')?></h3>
                <p>
                <?php
                Modal::begin(
                    [
                        'id' =>'modalEditMaxTime',
                        'toggleButton' => [
                            'label' => Yii::t('app', 'Change max time hike'),
                            'id' =>'modalEditMaxTimeButton',
                            'class' => 'btn  btn-xs btn-success',
                            'disabled' => !Yii::$app->user->identity->isActionAllowed('event-names', 'set-max-time'),
                        ],
                        'closeButton' => [
                            'label' => 'Close',
                            'class' => 'btn btn-danger btn-sm pull-right',
                        ],
                        'size' => Modal::SIZE_LARGE,
                    //'options' => ['class'=>'slide'],
                    ]
                );
                echo $this->render('/event-names/_form', ['model' => $eventModel, 'action' => 'set_max_time']);
                Modal::end();
                ?>
                </p>
                <p>
                <?php
                Modal::begin(
                    [
                        'id' =>'modalEditSettings',
                        'toggleButton' => [
                            'label' => Yii::t('app', 'Change settings hike'),
                            'id' =>'modalEditSettingsButton',
                            'class' => 'btn btn-xs btn-success',
                            'disabled' => $eventModel->status !== EventNames::STATUS_opstart,
                        ],
                        'closeButton' => [
                            'label' => 'Close',
                            'class' => 'btn btn-danger btn-sm pull-right',
                        ],
                        'size' => Modal::SIZE_LARGE,
                    //'options' => ['class'=>'slide'],
                    ]
                );
                echo $this->render('/event-names/_form', ['model' => $eventModel]);
                Modal::end();
                ?>
                </p>
                <p>
                <?php
                Modal::begin(
                    [
                        'id' =>'modalAssingBonuspoints',
                        'toggleButton' => [
                            'label' => Yii::t('app', 'Assign bonuspoints'),
                            'id' =>'modalAssingBonuspointsButton',
                            'class' => 'btn btn-xs btn-success',
                            'disabled' => $eventModel->status === EventNames::STATUS_opstart,
                        ],
                        'closeButton' => [
                            'label' => 'Close',
                            'class' => 'btn btn-danger btn-sm pull-right',
                        ],
                        'size' => Modal::SIZE_LARGE,
                    //'options' => ['class'=>'slide'],
                    ]
                );
                echo $this->render('/bonuspunten/_form', ['model' => new Bonuspunten()]);
                Modal::end();
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
            </div>
            <div class="col-sm-6">
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
                  'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
              ]);
              ?>
            </div>

            <div class="col-sm-3 well">
              <div class="thumbnail">
                <?php
                $form = ActiveForm::begin([
                    'options'=>['enctype'=>'multipart/form-data'],
                    'action' => ['event-names/upload'],// important
                ]); ?>
                <p>
                <?php
                // your fileinput widget for single file upload
                echo $form->field($eventModel, 'image_temp')->widget(FileInput::classname(), [
                    'options'=>['accept'=>'image/*'],
                    'disabled' => !Yii::$app->user->identity->isActionAllowed('event-names', 'upload'),
                    'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'jpeg', 'gif','png'],
            ////            'uploadUrl' => Url::to(['/organisatie/overview']),
            //                'previewFileType' => 'image',
            //                'showCaption' => false,
            //                'showRemove' => true,
            //                'showUpload' => true,
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' => '', //Yii::t('app', 'Select Photo')
            ]
                ]);
                ActiveForm::end();
                ?>
                </p>
              </div>
              <?php
              ?>
             <p>
             <?php
             Modal::begin(
                 [
                     'id' =>'modal-add-organisation',
                     'options' => [
                         'id' => 'modal-add-organisation',
                         'tabindex' => true // important for Select2 to work properly
                     ],
                     'toggleButton' => [
                         'label' => Yii::t('app', 'Add organisation to hike'),
                         'id' => 'modalAddOrganisationButton',
                         'class' => 'btn btn-xs btn-success',
                         'disabled' => !Yii::$app->user->identity->isActionAllowed('deelnemers-event', 'create'),
                     ],
                     'closeButton' => [
                         'label' => 'Close',
                         'class' => 'btn btn-danger btn-sm pull-right',
                     ],
                     'size' => Modal::SIZE_LARGE,
                 ]
             );
             $modelNewDeelnemer = new DeelnemersEvent;
             echo $this->render('/deelnemers-event/_form', ['model' => $modelNewDeelnemer]);
             Modal::end();?>
             </p>
             <?php
              echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => FALSE,
                  'dataProvider' => $organisatieData,
                  'itemView' => '/deelnemers-event/_list',
                  'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
              ]); ?>
              <p>
              <?php
              Modal::begin(
                  [
                      'id' =>'modalCreateGroup',
                      'toggleButton' => [
                          'label' => Yii::t('app', 'Add group to hike'),
                          'class' => 'btn btn-xs btn-success',
                          'disabled' => !Yii::$app->user->identity->isActionAllowed('groups', 'create'),
                      ],
                      'closeButton' => [
                          'label' => 'Close',
                          'class' => 'btn btn-danger btn-sm pull-right',
                      ],
                      'size' => Modal::SIZE_LARGE,
                  //'options' => ['class'=>'slide'],
                  ]
              );
              $modelNewGroups = new Groups();
              echo $this->render('/groups/_form', ['model' => $modelNewGroups]);
              Modal::end();
              ?>
              </p>

              <?php
              echo ListView::widget([
                  'summary' => FALSE,
                  'pager' => FALSE,
                  'dataProvider' => $groupsData,
                  'itemView' => '/groups/_list',
                  'emptyText' => 'Er zijn nog geen groepen aangemaakt voor deze hike.',
              ]);
              ?>
            </div>
          </div>
        </div>
    </div>
