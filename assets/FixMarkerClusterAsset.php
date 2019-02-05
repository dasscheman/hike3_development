<?php
namespace app\assets;

use \dosamigos\leaflet\plugins\markercluster\MarkerClusterAsset;

/**
 * FixMarkerClusterAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\leaflet\plugins\markercluster
 */
class FixMarkerClusterAsset extends MarkerClusterAsset
{
    public $css = [
        'MarkerCluster.css',
        'MarkerCluster.Default.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'dosamigos\leaflet\LeafLetAsset',
    ];

    public function init()
    {
        $this->sourcePath = \Yii::getAlias('@bower').'/leaflet.markercluster/dist';
        $this->js = YII_DEBUG ? ['leaflet.markercluster-src.js'] : ['leaflet.markercluster.js'];
    }


}
