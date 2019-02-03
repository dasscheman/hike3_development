<?php

$ip = require(__DIR__ . '/../config/ip_white_list.php');

if($_SERVER['HTTP_HOST'] == 'test.kiwi.run' ||
    $_SERVER['HTTP_HOST'] == 'test.hike-app.nl' ||
    $_SERVER['HTTP_HOST'] == 'hike.devel' ||
    in_array(@$_SERVER['REMOTE_ADDR'], $ip )){
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
