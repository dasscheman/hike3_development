<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use kartik\widgets\AlertBlock;

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
<?php $this->beginBody() ?>

<div class="wrap">
    <?php     
    NavBar::begin([
        'brandLabel' => !Yii::$app->user->isGuest ? 'Geselecteerde hike: ' . Yii::$app->user->identity->selected_event_ID: 'Hike-app.nl',
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
                        'label' => Yii::t('app','Search Friends'), 
                        'url'=>[
                            '/users/index', 
                            'id' => Yii::$app->user->id],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'index'),
                    ],
                    [
                        'label' => Yii::t('app','Select Hike'), 
                        'url' => ['/users/selectHike'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'selectHike'),
                    ],
//                    Leave this for now, only implement this when modal popup is not usable on mobile.
//                    [
//                        'label' => Yii::t('app','Account Settings') . ' ' . \Yii::$app->user->id, 
//                        'url' => [
//                            'users/update', 
//                            'id' => Yii::$app->user->id
//                        ],
//                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'update'),
//                    ],
                    [
                        'label' => Yii::t('app','Change Password'), 
                        'url' => ['/users/changePassword'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('users', 'changePassword'),
                    ],  
                    [
                        'label' => Yii::t('app','Create Account'), 
                        'url' => ['/users/create', 'language'=> 'nl'],
                        'visible' => Yii::$app->user->isGuest,
                    ],  
                    [
                        'label' => Yii::t('app','Forgot Password'), 
                        'url' => ['/users/resendPasswordUser', 'language'=> 'nl'],
                        'visible' => Yii::$app->user->isGuest,
                    ],                   
                ],
            ],
            Yii::$app->user->isGuest ? '':
            ['label' => Yii::t('app','Deelnemer'),                 
                'items' => [
                    [
                        'label'=> Yii::t('app','Check Answers'),
                        'url'=> ['openVragenAntwoorden/viewControle'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openVragenAntwoorden', 'viewControle')
                    ],
                    [
                        'label'=> Yii::t('app','Assign Bonus Points'),
                        'url'=>['bonuspunten/create'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('bonuspunten', 'create')
                    ],
                    [
                        'label'=> Yii::t('app','Answered Questions'),
                        'url'=>
                        [
                            'openVragenAntwoorden/index',
                        ],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openVragenAntwoorden', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Opened Hints'),
                        'url'=>['openNoodEnvelop/index',
                            'previous'=>'game/gameOverview'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openNoodEnvelop', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Bonus Points'),
                        'url'=>['bonuspunten/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('bonuspunten', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Checked Stations'),
                        'url'=>['postPassage/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('postPassage', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Qr'),
                        'url'=>['QrCheck/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('qrCheck', 'index')
                    ],       
                ],
            ],            
            Yii::$app->user->isGuest ? '':
            ['label' => Yii::t('app','Organisatie'),                
                'items' => [
                    [
                        'label' => Yii::t('app','Start New Hike'), 
                        'url'=>['/event-names/create'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('eventNames', 'create'),
                    ], 
                    [
                        'label' => Yii::t('app','Hike overzicht'), 
                        'url'=>['/organisatie/overview'],
                        'visible' => Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('organisatie', 'overview'),
                    ], 
                    [
                        'label'=>Yii::t('app', 'Introductie'),
                        'url'=>[
                            '/Route/viewIntroductie',
                            'introduction'=>true],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('route', 'viewIntroductie')
                    ],
                    [
                        'label'=>'Route Overzict',
                        'url'=>['/route/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('route', 'index')
                    ],
                    [
                        'label'=>'Posten Beheren',
                        'url'=>['/posten/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('posten', 'index')
                    ],
                    [
                        'label'=>'Vragen Overzicht',
                        'url'=>['/openVragen/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('openVragen', 'index')
                    ],
                    [
                        'label'=>'Hints Overzicht',
                        'url'=>['/noodEnvelop/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('noodEnvelop', 'index')
                    ],
                    [
                        'label'=>'Stille Posten Overzicht',
                        'url'=>['/qr/index'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('qr', 'index')
                    ],
                    [
                        'label'=>'Deelnemers Toevoegen',
                        'url'=>['/deelnemersEvent/create'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('deelnemersEvent', 'create')
                    ],
                    [
                        'label'=>'Groep Aanmaken',
                        'url'=>['/groups/create'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('groups', 'create')
                    ],
                    [
                        'label'=>'Dag Veranderen',
                        'url'=>['/eventNames/changeDay'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('eventNames', 'changeDay')
                    ],
                    [
                        'label'=>'Status Veranderen',
                        'url'=>['/eventNames/changeStatus'],
                        'visible'=> Yii::$app->user->isGuest ? FALSE : Yii::$app->user->identity->isActionAllowed('eventNames', 'changeStatus')
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
            ['label' => 'About', 'url' => ['/site/about']],
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
