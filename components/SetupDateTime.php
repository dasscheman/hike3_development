<?php

namespace app\components;

use Yii;
use app\models\DeelnemersEvent;

/*
 * Fix issues with time datetime and date.
 * Call deze functions with: Yii::$app->setupdatetime->
 */

class SetupDateTime
{
    // This are the formats wee will use from now on as standards.
    const DAY_FORMAT = 'php:d';
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const DATETIME_FORMAT_NO_SEC = 'php:Y-m-d H:i:';
    const TIME_FORMAT = 'php:H:i:s';

    public static function convert($dateStr, $type='date', $format = null)
    {
        if($format != null) {
            $fmt = $format;
        } else {
            switch ($type) {
                case 'datetime':
                    $fmt = self::DATETIME_FORMAT;
                    break;
                case 'datetime_no_sec':
                    $fmt = self::DATETIME_FORMAT_NO_SEC;
                    break;
                case 'time':
                    $fmt = self::TIME_FORMAT;
                    break;
                case 'day':
                    $fmt = self::DAY_FORMAT;
                    break;
                default:
                    $fmt = self::DATE_FORMAT;
                    break;
            }
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    /*
     * All Datetime, time and date field should be stored with this function.
     * To garante consistencie. This function can be used on any datetime, date,
     * time field just before the 'save'.
     */
    public static function storeFormat($dateStr, $type='date')
    {
        if ($type === 'datetime') {
            $fmt = self::DATETIME_FORMAT;
        } elseif ($type === 'time') {
            $fmt = self::TIME_FORMAT;
        } else {
            $fmt = self::DATE_FORMAT;
        }
        return Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    /*
     * All Datetime, time and date field should be displayed with this function.
     * To garante consistencie. This function can be used on any datetime, date,
     * time field.
     */
    public static function displayFormat($dateStr, $type='date', $absoluteTime = false, $alternate = false)
    {
        if ($absoluteTime) {
            // Krijg niet het de goede tijd terug waneer de looptijd berekend wordt.
            // Werkt wel wanneer ik hier UTC gebruik. Naderhand weer terug gezet.
            Yii::$app->formatter->timeZone = "UTC";
        }
        $time = self::convert($dateStr, $type);

        $alternate_time = array_key_exists(Yii::$app->user->identity->selected_event_ID, Yii::$app->params["alternate_time"]);

        if($alternate && $alternate_time) {
            $add_time = Yii::$app->params["alternate_time"][Yii::$app->user->identity->selected_event_ID]['add'];
            $factor = Yii::$app->params["alternate_time"][Yii::$app->user->identity->selected_event_ID]['factor'];
            if(gettype($dateStr) == 'integer') {
                // is niet heel mooi, maar zo kort voor de hike even pragmatisch
                $dateStr = $dateStr * $factor;
            } else {
                $dateStr = strtotime($dateStr) * $factor + $add_time;
            }
            $time = self::convert($dateStr, $type);
        }
        \Yii::$app->formatter->timeZone =  \Yii::$app->getTimeZone();
        return $time;
    }

    public static function displayRealTime($dateStr, $type='date') {
        if((Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_organisatie ||
        Yii::$app->user->identity->getRolUserForEvent() === DeelnemersEvent::ROL_post) &&
        array_key_exists(Yii::$app->user->identity->selected_event_ID, Yii::$app->params["alternate_time"])){
            return self::displayFormat($dateStr, $type, false, false);
        }
        return false;
    }


    public static function getDay($dateStr)
    {
        return \Yii::$app->formatter->asDate($dateStr, 'php:l');
    }
}
