<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\NewsletterMailList;

/**
 * Test controller
 */
class CronController extends Controller
{
    public function actionIndex()
    {
        echo "cron service runnning";
    }

    public function actionFrequent()
    {
    }

    public function actionHour()
    {
        // called every two minutes
        // */2 * * * * ~/sites/www/yii2/yii cron/day

        $time_start = microtime(true);
        $aantal = NewsletterMailList::sendNewswletters();
        echo 'Er zijn '.($aantal).' emails verzonden';
        echo "\n";
        $time_end = microtime(true);
        echo date("l jS \of F Y h:i:s A") . ': Processing for '.($time_end-$time_start).' seconds';
        echo "\n\n";
    }

    public function actionDay()
    {
    }

    public function actionMonth()
    {
    }
}
