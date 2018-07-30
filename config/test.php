<?php

require_once(__DIR__.'/debug.php');
$params = require(__DIR__ . '/params.php');
$keys = require(__DIR__ . '/keys.php');

$config = [
    'id' => 'basic',
    'name' => 'hike-app.nl',
    'basePath' => dirname(__DIR__),
    'aliases' => [
        '@kvgrid' => '/vendor/kartik-v',
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap' => [
        'log',
        [
            'class' => 'app\components\LanguageSelector',
            'supportedLanguages' => ['nl_NL'],
            // TODO:
            //'supportedLanguages' => ['en_US', 'nl_NL'],
        ],
        'app\components\Bootstrap',
        'devicedetect'
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'app\models\Users',
                'LoginForm' => 'app\models\LoginForm',
            ],
            'controllerMap' => [
                'admin' => 'app\controllers\user\AdminController',
                'registration' => 'app\controllers\user\RegistrationController',
                'recovery' => 'app\controllers\user\RecoveryController',
                'security' => 'app\controllers\user\SecurityController',
            ],
            'mailer' => [
                'sender' => ['noreply@hike-app.nl' => 'hike-app.nl'],
                'viewPath' => '@app/mail/user',
            ],
            'admins' => ['dasman']
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
        'gridview' =>  [
             'class' => '\kartik\grid\Module'
        ],
        // Configure text editor module
        'redactor' => 'yii\redactor\RedactorModule',
    ],
    'timeZone' => 'Europe/Amsterdam', // this is my default
    'components' => [
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    //'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => $keys['cookieValidationKey'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/users'
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
            'maxSourceLines' => 20,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_ENV == 'dev' || YII_ENV == 'test' ? true : false,
            'transport' => require(__DIR__ . '/email.php')
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'setupdatetime' => [
            'class' => 'app\components\SetupDateTime',
        ],
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => $keys['google_key'],
                        'language' => 'nl',
                        'version' => '3.1.18'
                    ]
                ]
            ]
        ],
        'db' => require(__DIR__ . '/test_db.php'),
        'devicedetect' => [
		'class' => 'alexandernst\devicedetect\DeviceDetect'
	],
    ],
    'params' => $params,
];

return $config;
