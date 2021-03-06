<?php

use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\ListView;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use kartik\editable\Editable;
use app\models\EventNames;
use app\components\CustomAlertBlock;

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
                    'delay' => false,
                ]);
                Modal::begin(['id'=>'main-modal']);
                echo '<div id="main-content-modal"></div>';
                Modal::end();
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
                    <br>
                    <p>
                        <?php
                        Modal::begin([
                           'toggleButton' => [
                               'label' => Yii::t('app', 'Change settings hike'),
                               'id' => 'modalChangeSettingsButton',
                               'class' => 'btn btn-xs btn-success',
                               'disabled' => !Yii::$app->user->can('organisatieOpstart'),
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
                        ?>
                    </p>
                    <p>
                        <?php
                        Modal::begin([
                            'toggleButton' => [
                                'label' => Yii::t('app', 'Pas status aan'),
                                'id' => 'modalChangeMaxTimeButton',
                                'class' => 'btn btn-xs btn-success',
                                // 'disabled' => !Yii::$app->user->can('organisatieGestart'),
                            ],
                        ]);
                        echo $this->render('/event-names/update', [
                            'model' => $eventModel,
                            'action' => 'set_change_status']);
                        Modal::end();?>
                    </p>
                </div>
                <?php
                Modal::begin(
                    [
                        'id' =>'modalCreateGroup',
                        'toggleButton' => [
                            'label' => Yii::t('app', 'Voeg een groep toe'),
                            'class' => 'btn btn-xs btn-success',
                            'disabled' => !Yii::$app->user->can('organisatie'),
                        ],
                    ]
                );

                echo $this->render('/groups/create', ['model' => $groupModel]);
                Modal::end();
                ?>
                </p>

                <?php
                echo ListView::widget([
                    'summary' => false,
                    'pager' => false,
                    'dataProvider' => $groupsData,
                    'itemView' => '/groups/_list',
                    'emptyText' => Yii::t('app', 'Deze hike heeft nog geen groepen.'),
                ]);


                echo ListView::widget([
                    'summary' => false,
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
                    'emptyText' => Yii::t('app', 'Nog geen activiteit.'),
                ]); 
                ?>
            </div>
            <?php
            if (!Yii::$app->devicedetect->isMobile()) {
                ?>
                <div class="col-sm-6">
                    <?php

                    echo Yii::$app->controller->renderPartial('/routebook/view', [
                        'model' => $routebookModel,
                        'vragen' => true,
                        'openVragen' => false,
                        'beantwoordeVragen' => false,
                        'hints' => true,
                        'closedHints' => true,
                        'openHints' => false,
                        'qr' => true,
                        'qrCheck' => false,
                        'group_id' => null,
                        'timeTableData' => $timeTableData
                    ]);

                    ?>
                </div>
            <?php
            } ?>
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
                        'disabled' => !Yii::$app->user->can('organisatie'),
                        'pluginOptions'=>[
                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif','png'],
                            'uploadLabel' => Yii::t('app', 'save'),
                            'removeLabel' => '',
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                            'browseLabel' => '',
                            'showPreview' => false
                        ]
                    ]);
                    ActiveForm::end();
                    ?>
                    </p>
                </div>

                <div class="thumbnail">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'test',
                        // 'options'=>['enctype'=>'multipart/form-data'],
                        'action' => ['route-track/upload-track','event_ID' => $eventModel->event_ID],// important
                    ]); ?>
                    <p>
                    <?php
                    // your fileinput widget for single file upload
                    echo $form->field($routeTrackModel, 'track_temp')->widget(FileInput::classname(), [
                        'options'=>['accept'=>'xml'],
                        'disabled' => !Yii::$app->user->can('organisatie'),
                        'pluginOptions'=>[
                            'allowedFileExtensions' => ['gpx'],
                            'uploadLabel' => Yii::t('app', 'save'),
                            'browseClass' => 'btn btn-primary btn-block',
                            'browseIcon' => '<i class="glyphicon glyphicon-road"></i> ',
                            'browseLabel' => '',
                            'showPreview' => false,
                            'removeLabel' => '',
                        ]
                    ]);
                    ActiveForm::end();
                    ?>
                    </p>
                    <p>
                    <?php
                    echo Html::a(
                        Yii::t('app', 'Bewerken van tracks'),
                        [
                            'route-track/edit-track',
                        ],
                        [
                            'title' => Yii::t('app', 'Bewerken van tracks'),
                            'class'=>'btn btn-primary btn-xs',
                        ]
                    );
                    ?> </p>
                </div>

                <div class="well">
                    <p>
                        <?php
                        echo Html::a(
                            Yii::t('app', 'Exporteer Hike'),
                            [
                                'export-import/export-route',
                            ],
                            [
                                'title' => Yii::t('app', 'Exporteer een excel bestand van de hike'),
                                'class'=>'btn btn-primary btn-xs',
                            ]
                        );
                    ?> </p>
                    <p> <?php
                        echo Html::a(
                            Yii::t('app', 'Cache Flush'),
                            [
                                'site/cache-flush',
                            ],
                            [
                                'title' => Yii::t('app', 'Cache flush'),
                                'class'=>'btn btn-primary btn-xs',
                            ]
                        ); ?>
                    </p> <?php
//  TODO, import of the export file
//                    $form = ActiveForm::begin([
//                        'options'=>['enctype'=>'multipart/form-data'],
//                        'action' => ['export-import/import-route'],// important
//                    ]);

//                    // your fileinput widget for single file upload
//                    echo $form->field($importModel, 'importFile')->widget(FileInput::classname(), [
//                        'options'=>[
//                            'accept'=>'ods'
//                        ],
//                        'disabled' => !Yii::$app->user->identity->isActionAllowed('export-import', 'import-route'),
//                        'pluginOptions'=>[
//                            'allowedFileExtensions' => ['ods'],
//                            'uploadLabel' => Yii::t('app',  'upload'),
//                            'removeLabel' => '',
//                            'showPreview' => false,
//                            'showCaption' => false,
//                            'elCaptionText' => '#customCaption',
//                            'browseClass' => 'btn btn-primary btn-xs',
//                            'browseIcon' => '',
//                            'browseLabel' => Yii::t('app',  'Import route excel'),
//                        ]
//                    ])->label(false);
//                    ActiveForm::end();
//                    echo '<span id="customCaption" class="text-success">' . Yii::t('app', 'No file selected') . '</span>';
                ?>
                </div>

                <div class="well">
                    <h3><?php echo Yii::t('app', 'actions')?></h3>
                    <?php
                    if ($eventModel->status === EventNames::STATUS_opstart) {
                        ?>
                        <p>
                            <?php
                            echo Html::a(
                                    Yii::t('app', 'Route overzicht'),
                                    ['/route/index'],
                                    [
                                        'class' => 'btn btn-xs btn-success',
                                    ]
                ); ?>
                        </p>
                        <p>
                            <?php
                            echo Html::a(
                                    Yii::t('app', 'Posten overzicht'),
                                    ['/posten/index'],
                                    [
                                        'class' => 'btn btn-xs btn-success',
                                    ]
                            ); ?>
                        </p> <?php
                    }
                    ?>
                    <p>
                        <?php
                        Modal::begin([
                            'toggleButton' => [
                                'label' => Yii::t('app', 'bonus'),
                                'id' => 'modalAddBonusButton',
                                'class' => 'btn btn-xs btn-success',
                                // 'disabled' => !Yii::$app->user->can('organisatie') || Yii::$app->user->can('organisatieOpstart'),
                            ],
                        ]);
                        echo $this->render('/bonuspunten/create', [
                            'model' => new \app\models\Bonuspunten]);
                        Modal::end();
                        ?>
                    </p>
                </div>

                <p>
                    <?php
                        echo ListView::widget([
                            'summary' => false,
                            'pager' => false,
                            'dataProvider' => $dataProviderCheck,
                            'itemView' => '/open-vragen-antwoorden/_list-controle',
                            'emptyText' => Yii::t('app', 'Er zijn geen vragen die gecontroleerd moeten worden'),
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
                                'label' => Yii::t('app', 'Voeg organiator toe aan hike'),
                                'id' => 'modalAddOrganisationButton',
                                'class' => 'btn btn-xs btn-success',
                                'disabled' => !Yii::$app->user->can('organisatie'),
                            ],
                        ]
                    );

                    echo $this->render('/deelnemers-event/create', ['model' => $modelDeelnemer]);
                    Modal::end();?>
                </p>
                <?php
                echo ListView::widget([
                    'summary' => false,
                    'pager' => false,
                    'dataProvider' => $organisatieData,
                    'itemView' => '/deelnemers-event/_list',
                    'emptyText' => Yii::t('app', 'No organisation.'),
                ]); ?>
            </div>
            <?php
            if (Yii::$app->devicedetect->isMobile()) {
                ?>
                <div class="col-sm-3 well">
                    <?php
                    echo ListView::widget([
                        'summary' => false,
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
                    ]); ?>
                </div>
            <?php
            } ?>
        </div>
    </div>
</div>
