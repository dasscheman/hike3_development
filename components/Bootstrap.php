<?php

namespace app\components;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // Here you can refer to Application object through $app variable
        $app->params['event_images_path'] = $app->basePath . '/web/uploads/event_images/';
        $app->params['map_icons'] = $app->basePath . '/web/images/map_icons/';
        $app->params['qr_code_path'] = $app->basePath . '/web/qr/';
        $app->params['timetrail_code_path'] = $app->basePath . '/web/timetrail/';
        $app->params['kiwilogo'] = $app->basePath . '/web/images/kiwilogo.jpg';
        // $app->params['qr_code_path'] = $app->basePath . '/web/qr/';
    }
}
