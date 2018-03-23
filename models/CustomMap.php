<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Cookie;

class CustomMap extends Model
{
    public $kleuren = ['rood', 'geel', 'blauw', 'oranje', 'paars', 'groen'];
    public $counts = [];


    public function setCookieIndexRoute($route_id)
    {
        $cookies = Yii::$app->getResponse()->getCookies();
        $cookies->remove('route_map_tab');
        $cookie = new Cookie([
            'name' => 'route_map_tab',
            'value' => $route_id,
            'expire' => time() + 86400 * 365,
        ]);
        $cookies->add($cookie);
    }
}
