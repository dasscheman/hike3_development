<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * TimeTableAsset
 *
 */
class TrackerAsset extends AssetBundle
{
   // public $basePath = '@webroot';
   // public $baseUrl = '@web';
   // public $sourcePath = '@bower/timetable/dist';

       // public $sourcePath = '@web';

    public $js = [
        '/js/tracker.js',
    ];

    // public $css = [
    //     'styles/timetablejs.css',
    // ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset'
    ];

   public $jsOptions = array(
        'position' => 3 // View::POS_HEAD // too high
        //'position' => View::POS_READY // in the html disappear the jquery.jrac.js declaration
        //'position' => View::POS_LOAD // disappear the jquery.jrac.js
         // 'position' => View::POS_END // appear in the bottom of my page, but jquery is more down again
   );
}
