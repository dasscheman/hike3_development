<?php

namespace app\components;

/*
 * Fix issues with time datetime and date.
 * Call deze functions with: Yii::$app->setupdatetime->
 */

class SetupDateTime {
    // This are the formats wee will use from now on as standards.
    const DAY_FORMAT = 'php:d';
    const DATE_FORMAT = 'php:Y-m-d';
    const DATETIME_FORMAT = 'php:Y-m-d H:i:s';
    const TIME_FORMAT = 'php:H:i:s';

    public static function convert($dateStr, $type='date', $format = null) {
        if ($type === 'datetime') {
              $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
        }
        elseif ($type === 'time') {
              $fmt = ($format == null) ? self::TIME_FORMAT : $format;
        }
        elseif ($type === 'days') {
              $fmt = ($format == null) ? self::DAY_FORMAT : $format;
        }
        else {
              $fmt = ($format == null) ? self::DATE_FORMAT : $format;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    /*
     * All Datetime, time and date field should be stored with this function.
     * To garante consistencie. This function can be used on any datetime, date,
     * time field just before the 'save'.
     */
    public static function storeFormat($dateStr, $type='date') {
        if ($type === 'datetime') {
              $fmt = self::DATETIME_FORMAT;
        }
        elseif ($type === 'time') {
              $fmt = self::TIME_FORMAT;
        }
        else {
              $fmt = self::DATE_FORMAT;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    /*
     * All Datetime, time and date field should be stored with this function.
     * To garante consistencie. This function can be used on any datetime, date,
     * time field just before the 'save'.
     */
    public static function displayFormat($dateStr, $type='date', $absoluteTime = FALSE) {
        \Yii::$app->formatter->timeZone =  \Yii::$app->getTimeZone();
        if ($type === 'datetime') {
              $fmt = 'php:d-m-Y H:i:s';
        }
        elseif ($type === 'time') {
              $fmt = self::TIME_FORMAT;
        }
        else {
              $fmt = 'php:d-m-Y';
        }

        if($absoluteTime) {
            // Krijg niet het de goede tijd terug waneer de looptijd berekend wordt.
            // Werkt wel wanneer ik hie UTC gebruik. Naderhand weer terug gezet.
            \Yii::$app->formatter->timeZone = "UTC";
        }
        $time = \Yii::$app->formatter->asDate($dateStr, $fmt);
        \Yii::$app->formatter->timeZone =  \Yii::$app->getTimeZone();
        return $time;

    }

    public static function getDay($dateStr) {
        return \Yii::$app->formatter->asDate($dateStr, 'php:l');
    }
}
