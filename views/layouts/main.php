<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\widgets\AlertBlock;
use app\models\EventNames;

use Yii;

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
        'brandLabel' => !Yii::$app->user->isGuest && Yii::$app->user->identity->selected ? 'Geselecteerde hike: ' . (EventNames::getEventName(Yii::$app->user->identity->selected)): 'Hike-app.nl',
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
                        'url'=>['/users/view',
                            'id' => Yii::$app->user->id
                        ],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'view'),
                    ],
                    [
                        'label' => Yii::t('app','Search New Friends'),
                        'url'=>[
                            '/users/search-new-friends',
                            'id' => Yii::$app->user->id],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'search-new-friends'),
                    ],
                    [
                        'label' => Yii::t('app','Friends'),
                        'url'=>[
                            '/users/search-friends',
                            'id' => Yii::$app->user->id],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'search-friends'),
                    ],
                    [
                        'label' => Yii::t('app','Friend Requests'),
                        'url'=>[
                            '/users/search-friend-requests',
                            'id' => Yii::$app->user->id],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'search-friend-requests'),
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
                ],
            ],
            // TODO deze moet later gebruikt worden maar voor nu even niet om te zien of de checks goed gaan.
            // Yii::$app->user->isGuest || !Yii::$app->user->identity->selected ? '':
            Yii::$app->user->isGuest ? '':
            ['label' => Yii::t('app','Game'),
                'items' => [
//                    [
//                        'label'=> Yii::t('app','Answered Questions'),
//                        'url'=>
//                        [
//                            'openVragen-antwoorden/index',
//                        ],
//                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openVragenAntwoorden', 'index')
//                    ],
                    [
                        'label'=> Yii::t('app','Game overview'),
                        'url' => ['site/game-overview'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('site', 'index')
                    ],
                    [
                        'label' => Yii::t('app','Overview groups scores'),
                        'url' => ['groups/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('groups', 'index')
                    ],
//                    [
//                        'label'=> Yii::t('app','Opened Hints'),
//                        'url'=>['open-nood-envelop/index',
//                            'previous'=>'game/gameOverview'],
//                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openNoodEnvelop', 'index')
//                    ],
                    [
                        'label'=> Yii::t('app','Passed Stations & bonuspoints'),
                        'url'=>['groups/index-posten'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('groups', 'index-posten')
                    ],
//                    [
//                        'label'=> Yii::t('app','Qr'),
//                        'url'=>['QrCheck/index'],
//                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('qrCheck', 'index')
//                    ],
                ],
            ],
            // TODO deze moet later gebruikt worden maar voor nu even niet om te zien of de checks goed gaan.
            // Yii::$app->user->isGuest || !Yii::$app->user->identity->selected ? '':
            Yii::$app->user->isGuest ? '':
            ['label' => Yii::t('app','Organisatie'),
                'items' => [
                    [
                        'label' => Yii::t('app','Start New Hike'),
                        'url'=>['/event-names/create'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('eventNames', 'create'),
                    ],
                    [
                        'label' => Yii::t('app','Hike overview'),
                        'url'=>['/site/overview'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('organisatie', 'overview'),
                    ],
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
                       'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('noodEnvelop', 'index')
                   ],
                   [
                       'label'=>Yii::t('app', 'Overview checked silent stations'),
                       'url'=>['/qr-check/index'],
                       'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('qr', 'index')
                   ],
                    [
                        'label'=> Yii::t('app','Answers overview'),
                        'url'=> ['open-vragen-antwoorden/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openVragenAntwoorden', 'viewControle')
                    ],
                    [
                        'label'=> Yii::t('app','Bonus Points overview'),
                        'url'=>['bonuspunten/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('bonuspunten', 'create')
                    ],
                ]
            ],
            ['label' => Yii::t('app','Language'),
                'items' => [
                    [
                        'label' => Yii::t('app','English'),
                        'url' => ['/site/language', 'language'=> 'en'],
                    ],
                    [
                        'label' => Yii::t('app','Dutch'),
                        'url' => ['/site/language', 'language'=> 'nl'],
                    ],
                ],
            ],
            //['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post'],
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= AlertBlock::widget([
            'type' => AlertBlock::TYPE_ALERT,
            'useSessionFlash' => true,
            'delay' => 4000,

        ]); ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
