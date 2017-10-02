<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', '145.133.104.158'])) {
    // comment out the following two lines when deployed to production
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
