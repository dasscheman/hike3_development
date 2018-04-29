<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

// NOTE: Make sure this file is not accessible when deployed to production
if (YII_ENV_TEST && !in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', '145.133.104.158'])) {
    die('You are not allowed to access this file.');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
