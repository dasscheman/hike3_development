<?php

namespace app\models;

use dosamigos\leaflet\controls\Control;
use yii\web\JsExpression;

/**
 * renders locate control layer
 *
 * @see https://leafletjs.com/reference-1.3.4.html#control-locate
 */
class Locate extends Control
{
    /**
     * Returns the javascript ready code for the object to render
     * @return \yii\web\JsExpression
     */
    public function encode()
    {
        $this->clientOptions['position'] = $this->position;
        $options = $this->getOptions();
        $name = $this->getName();
        $map = $this->map;
        $js = "L.control.locate($options)" . ($map !== null ? ".addTo($map);" : "");
        if (!empty($name)) {
            $js = "var $name = $js" . ($map !== null ? "" : ";");
        }
        return new JsExpression($js);
    }
}
