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
class Proj4Asset extends AssetBundle
{
    public $sourcePath = '@npm/proj4/dist';
    public $js = [
        'proj4.js',
    ];

    public $depends = [
        'dosamigos\leaflet\LeafLetAsset',
    ];
}
