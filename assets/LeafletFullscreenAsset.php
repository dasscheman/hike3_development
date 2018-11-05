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
class LeafletFullscreenAsset extends AssetBundle
{
    public $sourcePath = '@bower/leaflet.fullscreen';
    public $css = [
        'Control.FullScreen.css'
    ];
    public $js = [
        'Control.FullScreen.js',
    ];

    public $depends = [
        'dosamigos\leaflet\LeafLetAsset',
    ];
}
