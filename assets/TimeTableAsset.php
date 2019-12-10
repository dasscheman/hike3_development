<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * TimeTableAsset
 *
 */
class TimeTableAsset extends AssetBundle
{
    public $sourcePath = '@bower/timetable/dist';


    // public $basePath = '@bower/timetable/dist'; //'@webroot';
    // public $baseUrl = '@bower/timetable/dist'; //'@web';
    public $js = [
        'scripts/timetable.js',
        '/js/timetable.js'

    ];

    public $css = [
        'styles/timetablejs.css',
    ];

    public $depends = [
        // 'yii\web\JqueryAsset',
        // 'yii\web\YiiAsset',
    ];

    public $jsOptions = array(
        'position' => 1 //View::POS_HEAD // too high
        //'position' => View::POS_READY // in the html disappear the jquery.jrac.js declaration
        //'position' => View::POS_LOAD // disappear the jquery.jrac.js
         // 'position' => View::POS_END // appear in the bottom of my page, but jquery is more down again
    );
}
