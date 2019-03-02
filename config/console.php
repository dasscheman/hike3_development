<?php

$ip = require(__DIR__ . '/../config/ip_white_list.php');
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic-console',
    'name' => 'hike-app.nl',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
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
        'db' => $db,
        'authManager' => [
            'class' => 'dektrium\rbac\components\DbManager',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => require(__DIR__ . '/email.php')
        ],
        'assetManager' => [
            'bundles' => [
        ]
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => $params['url']
        ]
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
                'viewPath' => '@app/mail/user',
            ],
            'admins' => ['dasman']
        ],
        'rbac' => 'dektrium\rbac\RbacWebModule',
    ],
    'params' => $params,
];

if (YII_ENV == 'dev') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $ip,
    ];
}

return $config;
