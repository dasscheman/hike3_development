<?php
// Created: 2014
// Modified: 21 feb 2015

namespace app\components;

/**
 * GeneralFunction bevat enkele algemen functies
 * Deze functies zijn neit perse gekoppeld aan een model
 */
class GeneralFunctions
{
    public $colors = [ 'AliceBlue','AntiqueWhite','Aqua','Aquamarine',
        'Azure','Beige','Black','BlanchedAlmond','Blue','BlueViolet',
        'Brown','BurlyWood','CadetBlue','Chartreuse','Chocolate','Coral',
        'CornflowerBlue','Cornsilk','Crimson','Cyan','DarkBlue','DarkCyan',
        'DarkGoldenRod','DarkGray','DarkGrey','DarkGreen','DarkKhaki',
        'DarkMagenta','DarkOliveGreen','Darkorange','DarkOrchid','DarkRed',
        'DarkSalmon','DarkSeaGreen','DarkSlateBlue','DarkSlateGray',
        'DarkSlateGrey','DarkTurquoise','DarkViolet','DeepPink','DeepSkyBlue',
        'DimGray','DimGrey','DodgerBlue','FireBrick','FloralWhite','ForestGreen',
        'Fuchsia','Gainsboro','GhostWhite','Gold','GoldenRod','Gray','Grey',
        'Green','GreenYellow','HoneyDew','HotPink','IndianRed','Indigo','Ivory',
        'Khaki','Lavender','LavenderBlush','LawnGreen','LemonChiffon',
        'LightBlue','LightCoral','LightCyan','LightGoldenRodYellow','LightGray',
        'LightGrey','LightGreen','LightPink','LightSalmon','LightSeaGreen',
        'LightSkyBlue','LightSlateGray','LightSlateGrey','LightSteelBlue',
        'LightYellow','Lime','LimeGreen','Linen','Magenta','Maroon',
        'MediumAquaMarine','MediumBlue','MediumOrchid','MediumPurple',
        'MediumSeaGreen','MediumSlateBlue','MediumSpringGreen',
        'MediumTurquoise','MediumVioletRed','MidnightBlue','MintCream',
        'MistyRose','Moccasin','NavajoWhite','Navy','OldLace','Olive',
        'OliveDrab','Orange','OrangeRed','Orchid','PaleGoldenRod','PaleGreen',
        'PaleTurquoise','PaleVioletRed','PapayaWhip','PeachPuff','Peru','Pink',
        'Plum','PowderBlue','Purple','Red','RosyBrown','RoyalBlue','SaddleBrown',
        'Salmon','SandyBrown','SeaGreen','SeaShell','Sienna','Silver','SkyBlue',
        'SlateBlue','SlateGray','SlateGrey','Snow','SpringGreen','SteelBlue',
        'Tan','Teal','Thistle','Tomato','Turquoise','Violet','Wheat','White',
        'WhiteSmoke','Yellow','YellowGreen'];

    /**
     * Returns Ja bij input 1 en Nee bij input 0
     */
    public static function getJaNeeText($yesno)
    {
        if ($yesno==0) {
            return "Nee";
        }
        if ($yesno==1) {
            return "Ja";
        }
    }

    /**
     * Returns true als gebruiker is ingeschreven voor 1 event dat de status
     * 'gestart' heeft. Anders return false.
     */
    public static function checkForSingleActiveEventForUser()
    {
        $count_gestart = 0;
        $dataDeelnemersEvent = DeelnemersEvent::model()->findAll(
            'user_ID = :user_id',
                               array(':user_id' => Yii::app()->user->id)
        );

        foreach ($dataDeelnemersEvent as $record) {
            $dataEventNames = EventNames::model()->find(
                'event_ID =:event_id',
                                    array(':event_id'=>$record->event_ID)
            );
            if ($dataEventNames->status == 3 or
               $dataEventNames->status == 2) {
                $count_gestart++;
            }
        }

        if ($count_gestart<>1) {
            return false;
        }
        return true;
    }

    /**
     * Returns event_id als gebruiker is ingeschreven voor 1 event dat de status
     * 'gestart' heeft. checkForSingleActiveEventForUser() Moet eerst afgecheckt worden.
     */
    public static function getSingleActiveEventIdForUser()
    {
        $dataDeelnemersEvent = DeelnemersEvent::model()->findAll(
            'user_ID = :user_id',
                               array(':user_id' => Yii::app()->user->id)
        );

        foreach ($dataDeelnemersEvent as $record) {
            $dataEventNames = EventNames::model()->find(
                'event_ID =:event_id',
                                    array(':event_id'=>$record->event_ID)
            );
            if ($dataEventNames->status == 3 or
               $dataEventNames->status == 2) {
                return $dataEventNames->event_ID;
            }
        }
    }

    /**
     * Returns true als gebruiker is ingeschreven voor 1 event.
     * Anders return false.
     */
    public static function checkForSingleEventForUser()
    {
        $count_gestart = 0;
        $dataDeelnemersEvent = DeelnemersEvent::model()->findAll(
            'user_ID = :user_id',
                               array(':user_id' => Yii::app()->user->id)
        );

        foreach ($dataDeelnemersEvent as $record) {
            $dataEventNames = EventNames::model()->find(
                'event_ID =:event_id',
                                    array(':event_id'=>$record->event_ID)
            );
            $count_gestart++;
        }

        if ($count_gestart<>1) {
            return false;
        }
        return true;
    }

    /**
     * Returns event_id als gebruiker is ingeschreven voor 1 event.
     * checkForSingleEventForUser() Moet eerst afgecheckt worden.
     */
    public static function getSingleEventIdForUser()
    {
        $dataDeelnemersEvent = DeelnemersEvent::model()->findAll(
            'user_ID = :user_id',
                               array(':user_id' => Yii::app()->user->id)
        );

        foreach ($dataDeelnemersEvent as $record) {
            $dataEventNames = EventNames::model()->find(
                'event_ID =:event_id',
                                    array(':event_id'=>$record->event_ID)
            );
            return $dataEventNames->event_ID;
        }
    }

    public static function randomString($length)
    {
        $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
    }

    public function printGlyphiconCheck($check)
    {
        if ($check) {
            return '<span class="glyphicon glyphicon-ok"></span>';
        } else {
            return '<span class="glyphicon glyphicon-remove"></span>';
        }
    }

    public function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    public function random_color()
    {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }
}
