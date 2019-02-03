<?php
/**
 *
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Leaflet Fullscreen application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LeafletLocateAsset extends AssetBundle
{
    public $sourcePath = '@bower/leaflet.locatecontrol/dist';
    public $css = [
        'L.Control.Locate.mapbox.css',
        'L.Control.Locate.css'
    ];
    public $js = [
        'L.Control.Locate.min.js',
    ];

    public $depends = [
        'dosamigos\leaflet\LeafLetAsset',
    ];
}
