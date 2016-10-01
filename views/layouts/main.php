<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Bonuspunten;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use app\models\Groups;
use app\models\OpenVragen;
use app\models\OpenVragenAntwoorden;
use app\models\OpenNoodEnvelop;
use app\models\Posten;
use app\models\PostPassage;
use app\models\Qr;
use app\models\QrCheck;
use app\models\Route;
use app\models\Users;

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
        'brandLabel' => !Yii::$app->user->isGuest ? 'Geselecteerde hike: ' . Yii::$app->user->identity->selected_event_ID: 'Niets geselecteerd',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $event_id = Yii::$app->request->get();
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => Yii::t('app','Profiel'),                 
                'items' => [
                    [
                        'label' => Yii::t('app','Search Friends'), 
                        'url'=>['/users/searchFriends'],
                        'visible' => Users::isActionAllowed('users', 'searchFriends'),
                    ],
                    [
                        'label' => Yii::t('app','Select Hike'), 
                        'url' => ['/users/selectHike'],
                        'visible' => Users::isActionAllowed('users', 'selectHike'),
                    ],
                    [
                        'label' => Yii::t('app','Account Settings') . \Yii::$app->user->id, 
                        'url' => ['users/update'],
                        'visible'=> Users::isActionAllowed('users', 'update'),
                    ],
                    [
                        'label' => Yii::t('app','Change Password'), 
                        'url' => ['/users/changePassword'],
                        'visible' => Users::isActionAllowed('users', 'changePassword'),
                    ],  
                    [
                        'label' => Yii::t('app','Create Account'), 
                        'url' => ['/users/create', 'language'=> 'nl'],
                        'visible' => Yii::$app->user->isGuest,
                    ],  
                    [
                        'label' => Yii::t('app','Forgot Password'), 
                        'url' => ['/users/changePassword', 'language'=> 'nl'],
                        'visible' => Users::isActionAllowed('users', 'changePassword'),
                    ],                   
                ],
            ],
            ['label' => Yii::t('app','Deelnemer'),                 
                'items' => [
                    [
                        'label'=> Yii::t('app','Check Answers'),
                        'url'=> ['openVragenAntwoorden/viewControle', 'event_id'=>$event_id],
                        'visible'=> OpenVragenAntwoorden::isActionAllowed('openVragenAntwoorden', 'viewControle')
                    ],
                    [
                        'label'=> Yii::t('app','Assign Bonus Points'),
                        'url'=>['bonuspunten/create', 'event_id'=>$event_id],
                        'visible'=> Bonuspunten::isActionAllowed('bonuspunten', 'create')
                    ],
                    [
                        'label'=> Yii::t('app','Answered Questions'),
                        'url'=>
                        [
                            'openVragenAntwoorden/index',
                            'event_id'=>$event_id,
                            'previous'=>'game/gameOverview'
                        ],
                        'visible'=> OpenVragenAntwoorden::isActionAllowed('openVragenAntwoorden', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Opened Hints'),
                        'url'=>['openNoodEnvelop/index',
                            'event_id'=>$event_id,
                            'previous'=>'game/gameOverview'],
                        'visible'=> OpenNoodEnvelop::isActionAllowed('openNoodEnvelop', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Bonus Points'),
                        'url'=>['bonuspunten/index',
                            'event_id'=>$event_id,
                            'previous'=>'game/gameOverview'],
                        'visible'=> Bonuspunten::isActionAllowed('bonuspunten', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Checked Stations'),
                        'url'=>['postPassage/index',
                            'event_id'=>$event_id,
                            'previous'=>'game/gameOverview'],
                        'visible'=> PostPassage::isActionAllowed('postPassage', 'index')
                    ],
                    [
                        'label'=> Yii::t('app','Qr'),
                        'url'=>['QrCheck/index',
                            'event_id'=>$event_id,
                            'previous'=>'game/gameOverview'],
                        'visible'=> QrCheck::isActionAllowed('qrCheck', 'index')
                    ],       
                ],
            ],
            ['label' => Yii::t('app','Organisatie'),                
                'items' => [
                    [
                        'label' => Yii::t('app','Start New Hike'), 
                        'url'=>['/event-names/create'],
                        'visible' => EventNames::isActionAllowed('eventNames', 'create'),
                    ], 
                    [
                        'label' => Yii::t('app','Hike overzicht'), 
                        'url'=>['/organisatie/overview'],
                        //'visible' => EventNames::isActionAllowed('organisatie', 'overview'),
                    ], 
                    [
                        'label'=>Yii::t('app', 'Hike instellingen'),
                        'url'=>['/event-names/update'],
                        //'visible'=> EventNames::isActionAllowed('eventNames', 'update')
                    ],
                    [
                        'label'=>Yii::t('app', 'Introductie'),
                        'url'=>[
                            '/Route/viewIntroductie',
                            'introduction'=>true],
                        'visible'=> Route::isActionAllowed('route', 'viewIntroductie')
                    ],
                    [
                        'label'=>'Route Beheren',
                        'url'=>[
                            '/route/index',
                        ],
                        'visible'=> Route::isActionAllowed('route', 'index')
                    ],
                    [
                        'label'=>'Posten Beheren',
                        'url'=>['/posten/index'],
                        'visible'=> Posten::isActionAllowed('posten', 'index')
                    ],
                    [
                        'label'=>'Vragen Overzicht',
                        'url'=>['/openVragen/index'],
                        'visible'=> OpenVragen::isActionAllowed('openVragen', 'index')
                    ],
                    [
                        'label'=>'Hints Overzicht',
                        'url'=>['/noodEnvelop/index'],
                        'visible'=> Qr::isActionAllowed('noodEnvelop', 'index')
                    ],
                    [
                        'label'=>'Stille Posten Overzicht',
                        'url'=>['/qr/index'],
                        'visible'=> Qr::isActionAllowed('qr', 'index')
                    ],
                    [
                        'label'=>'Deelnemers Toevoegen',
                        'url'=>['/deelnemersEvent/create'],
                        'visible'=> DeelnemersEvent::isActionAllowed('deelnemersEvent', 'create')
                    ],
                    [
                        'label'=>'Groep Aanmaken',
                        'url'=>['/groups/create'],
                        'visible'=> Groups::isActionAllowed('groups', 'create')
                    ],
                    [
                        'label'=>'Dag Veranderen',
                        'url'=>['/eventNames/changeDay'],
                        'visible'=> EventNames::isActionAllowed('eventNames', 'changeDay')
                    ],
                    [
                        'label'=>'Status Veranderen',
                        'url'=>['/eventNames/changeStatus'],
                        'visible'=> EventNames::isActionAllowed('eventNames', 'changeStatus')
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
