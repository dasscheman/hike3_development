<?php
/* @var $this \yii\web\View */
/* @var $content string */

// use Yii; 19 feb 2017: This generates this error in the functional test:
// yii\base\ErrorException: The use statement with non-compound name 'Yii' has no effect

use yii\helpers\Html;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\EventNames;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta name="theme-color" content="#002039" />
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody(); ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => !Yii::$app->user->isGuest && Yii::$app->user->identity->selected_event_ID ?
                    Html::img('@web/images/kiwilogo40-39.jpg', ['class' => 'img-circle', 'height' => "37", 'width' => "37"]) . EventNames::getEventName(Yii::$app->user->identity->selected_event_ID) :
                    Html::img('@web/images/kiwilogo40-39.jpg', ['class' => 'img-circle', 'height' => "37", 'width' => "37"]) . 'Kiwi.run',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);

            echo NavX::widget(
                [
                'options' => ['class' => 'navbar-nav navbar-right click-menu'],
                'items' => [
                    [
                        'label' => Yii::t('app', 'Profiel'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Overview'),
                                'url' => ['/users/view'],
                                'visible' => Yii::$app->user->isGuest ? false : true,
                            ],
                            [
                                'label' => Yii::t('app', 'Friends'),
                                'url' => [
                                    '/users/search-friends'],
                                'visible' => Yii::$app->user->isGuest ? false : true,
                            ],
                            [
                                'label' => Yii::t('app', 'Select Hike'),
                                'url' => ['/event-names/select-hike'],
                                'visible' => Yii::$app->user->isGuest ? false : true,
                            ],
                            [
                                'label' => Yii::t('app', 'Tracks'),
                                'url' => ['/track/index'],
                                'visible' => Yii::$app->user->isGuest ? false : true,
                            ],
                            [
                                'label' => Yii::t('app', 'Create Account'),
                                'url' => ['/users/create', 'language' => 'nl'],
                                'visible' => Yii::$app->user->isGuest,
                            ],
                            [
                                'label' => Yii::t('app', 'Forgot Password'),
                                'url' => ['/users/resend-password-user', 'language' => 'nl'],
                                'visible' => Yii::$app->user->isGuest,
                            ],
                            [
                                'label' => isset(Yii::$app->user->identity->voornaam) ? 'Logout ' . Yii::$app->user->identity->voornaam : '',
                                'url' => ['/user/security/logout'],
                                'linkOptions' => ['data-method' => 'post'],
                                'visible' => !Yii::$app->user->isGuest,
                            ],
                        ],
                    ],
                    !Yii::$app->user->can('gebruiker') ? '' :
                        ['label' => Yii::t('app', 'Game'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Overview groups scores'),
                                'url' => ['/groups/index'],
                                'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('gebruiker')
                            ],
                            [
                                'label' => Yii::t('app', 'Passed Stations & bonuspoints'),
                                'url' => ['/groups/index-posten'],
                                'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('gebruiker')
                            ],
                            [
                                'label' => Yii::t('app', 'Search hints'),
                                'url' => ['/nood-envelop/index'],
                                'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('gebruiker')
                            ],
                        ],
                        ],
                    !Yii::$app->user->can('organisatie') && !Yii::$app->user->can('deelnemer')? '' :
                        [
                        'label' => Yii::t('app', 'Map'),
                        'options' => [
                            'id' => 'map-click'],
                            'url' => ['/map/index'],
                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('Organisatie') || Yii::$app->user->can('deelnemer')
                        ],
                    !Yii::$app->user->can('organisatie') ? '' :
                        [
                            'label' => Yii::t('app', 'Organisatie'),
                            'items' => [
                                [
                                    'label' => Yii::t('app', 'Route Overview'),
                                    'url' => ['/route/index'],
                                    'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('Organisatie')
                                ],
                                [
                                    'label' => Yii::t('app', 'Stations'),
                                    'url' => ['/posten/index'],
                                    'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                ],
                                [
                                    'label' => Yii::t('app', 'Time Trails'),
                                    'url' => ['/time-trail/index'],
                                    'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                ],
                                [
                                    'label' => Yii::t('app', 'Overviews'),
                                    'items' => [
                                        [
                                            'label' => Yii::t('app', 'Activity groups'),
                                            'url' => ['/groups/index-activity'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Opened hints'),
                                            'url' => ['/open-nood-envelop/index'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Silent stations'),
                                            'url' => ['/qr-check/index'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Answers'),
                                            'url' => ['/open-vragen-antwoorden/index'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Bonus Points'),
                                            'url' => ['/bonuspunten/index'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie')
                                        ],
                                    ]
                                ],
                                [
                                    'label' => Yii::t('app', 'Print'),
                                    'items' => [
                                        [
                                            'label' => Yii::t('app', 'Print all silent stations'),
                                            'url' => ['/qr/print-all-pdf'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie'),
                                            'linkOptions' => [
                                                'target'=>'_blank'
                                            ]
                                        ],
                                        [
                                            'label' => Yii::t('app', 'Print all time trails'),
                                            'url' => ['/time-trail-item/print-all-pdf'],
                                            'visible' => Yii::$app->user->isGuest ? false : Yii::$app->user->can('organisatie'),
                                            'linkOptions' => [
                                                'target'=>'_blank'
                                            ]
                                        ],
                                    ]
                                ]
                            ],
                        ],
                    // TODO:
                    // ['label' => Yii::t('app','Language'),
                    //     'items' => [
                    //         [
                    //             'label' => Yii::t('app','English'),
                    //             'url' => ['/site/language', 'language'=> 'en'],
                    //         ],
                    //         [
                    //             'label' => Yii::t('app','Dutch'),
                    //             'url' => ['/site/language', 'language'=> 'nl'],
                    //         ],
                    //     ],
                    // ],
                    //['label' => 'About', 'url' => ['/site/about']],
                    ['label' => Yii::t('app', 'Info'),
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Quick start'),
                                'url' => ['/site/quick-start'],
                            ],
                            [
                                'label' => Yii::t('app', 'Contact'),
                                'url' => ['/site/contact'],
                            ],
                            [
                                'label' => Yii::t('app', 'About'),
                                'url' => ['/site/about'],
                            ],
                        ]
                    ],
                    !isset(Yii::$app->user->identity->isAdmin) || !Yii::$app->user->identity->isAdmin ? '' :
                    [
                        'label' => 'Admin',
                        'items' => [
                            [
                                'label' => Yii::t('app', 'Users'),
                                'url' => ['/user/admin/index'],
                            ],
                            [
                                'label' => Yii::t('app', 'Newsletters'),
                                'url' => ['/newsletter/index'],
                            ],
                            [
                                'label' => Yii::t('app', 'Newsletters que'),
                                'url' => ['/newsletter-mail-list/index'],
                            ],
                        ]
                    ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'Login', 'url' => ['/user/security/login']] :
                        '',
                ],
            ]
            );
            NavBar::end();

            if (Yii::$app->controller->id !== 'map') {
                ?>
                <div class="container">
            <?php
            } else {
                ?>
                <div class="container-map">
                <?php
            }
                    echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                    <?= $content ?>
                </div>
            </div>
            <?php
          if (Yii::$app->controller->id !== 'map') {
              ?>
            <footer class="footer">
               
                    <p class="pull-left">&copy; Kiwi.run <?= date('Y') ?></p>
                
            </footer>
            <?php
          };
            $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
