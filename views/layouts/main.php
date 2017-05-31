<?php

/* @var $this \yii\web\View */
/* @var $content string */

// use Yii; 19 feb 2017: This generates this error in the functional test:
// yii\base\ErrorException: The use statement with non-compound name 'Yii' has no effect

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\EventNames;
use app\models\DeelnemersEvent;
use yii\helpers\Url;


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
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
            Html::img('@web/images/kiwilogo40-39.jpg', ['class' => 'img-circle', 'height'=>"37", 'width'=>"37"]) . EventNames::getEventName(Yii::$app->user->identity->selected_event_ID):
            Html::img('@web/images/kiwilogo40-39.jpg', ['class' => 'img-circle', 'height'=>"37", 'width'=>"37"]) . 'Kiwi.run',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Yii::t('app','Profiel'),
                'items' => [
                    [
                        'label' => Yii::t('app','Overview'),
                        'url'=>['/users/view'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'view'),
                    ],
                    [
                        'label' => Yii::t('app','Friends'),
                        'url'=>[
                            '/users/search-friends'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'search-friends'),
                    ],
                    [
                        'label' => Yii::t('app','Select Hike'),
                        'url' => ['/event-names/select-hike'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : TRUE,
                    ],
                    [
                        'label' => Yii::t('app','Create Account'),
                        'url' => ['/users/create', 'language'=> 'nl'],
                        'visible' => Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => Yii::t('app','Forgot Password'),
                        'url' => ['/users/resend-password-user', 'language'=> 'nl'],
                        'visible' => Yii::$app->user->isGuest,
                    ],
                    [
                        'label' => isset(Yii::$app->user->identity->voornaam )?'Logout ' . Yii::$app->user->identity->voornaam: '',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post'],
                        'visible' => !Yii::$app->user->isGuest,
                    ],
                ],
            ],
            Yii::$app->user->isGuest || !Yii::$app->user->identity->selected_event_ID ? '':
            ['label' => Yii::t('app','Game'),
                'items' => [
                    [
                        'label' => Yii::t('app','Overview groups scores'),
                        'url' => ['groups/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('groups', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Passed Stations & bonuspoints'),
                        'url'=>['groups/index-posten'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('groups', 'index-posten')
                    ],
                ],
            ],
            !Yii::$app->user->identity->selected_event_ID ||
            DeelnemersEvent::getRolOfCurrentPlayerCurrentGame() > DeelnemersEvent::ROL_post? '':
            ['label' => Yii::t('app','Organisatie'),
                'items' => [
                    [
                        'label'=> Yii::t('app','Route Overview'),
                        'url'=>['/route/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('route', 'index')
                    ],
                    [
                        'label'=>Yii::t('app','Stations'),
                        'url'=>['/posten/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('posten', 'index')
                    ],
                   [
                       'label'=>Yii::t('app', 'Overview opened hints'),
                       'url'=>['/open-nood-envelop/index'],
                       'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('open-nood-envelop', 'index')
                   ],
                   [
                       'label'=>Yii::t('app', 'Overview checked silent stations'),
                       'url'=>['/qr-check/index'],
                       'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('qr', 'index')
                   ],
                    [
                        'label'=> Yii::t('app','Answers overview'),
                        'url'=> ['open-vragen-antwoorden/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('open-vragen-antwoorden', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Bonus Points overview'),
                        'url'=>['bonuspunten/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('bonuspunten', 'index')
                    ],
                ]
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
            ['label' => Yii::t('app','Info'),
                'items' => [
                    [
                        'label' => Yii::t('app','Contact'),
                        'url'=>['/site/contact'],
                    ],
                    [
                        'label'=> Yii::t('app','About'),
                        'url'=>['/site/about'],
                    ],
                ]
            ],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                '',
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Kiwi.run <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
