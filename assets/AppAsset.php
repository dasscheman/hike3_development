<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'css/imagepopup.css'
    ];

    public $js = [
        // 'js/tracker.js',
        'js/site.js',
        'js/countdown.js',
        'js/disablestatus.js',
        'js/imagepopup.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\LeafletFullscreenAsset',
        'app\assets\LeafletLocateAsset',
        'app\assets\Proj4Asset',
        'app\assets\FixMarkerClusterAsset',
        'app\assets\TimeTableAsset',
        // 'app\assets\TrackerAsset'
    ];
}
