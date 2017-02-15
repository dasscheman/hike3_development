<?php
/**
 * Application configuration shared by all test types
 */
return [
    'language' => 'en-US',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'fixtureDataPath' => '@tests/codeception/fixtures',
            'templatePath' => '@tests/codeception/templates',
            'namespace' => 'tests\codeception\fixtures',
        ],
    ],
    'components' => [
        'db' => [
            'dsn' => 'mysql:host=localhost;dbname=hike_v3_01_test',
            'username' => 'test',
            'password' => 'test',
            // 'dump' => 'codeception/_data/hike-v2-01.sql',
            // 'populate' => true,
            // 'cleanup' => true
        ],
//         'mailer' => [
//             'useFileTransport' => true,
//         ],
//         'urlManager' => [
//             'showScriptName' => true,
//         ],
    ],
];
