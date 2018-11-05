<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use RDConverter\RDConverter;

abstract class HikeActiveRecord extends ActiveRecord
{

    /**
    * Attaches the timestamp behavior to update our create and update times
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_ID',
                'updatedByAttribute' => 'update_user_ID',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
                'value' => function () {
                    return \Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
                },
            ],
        ];
    }

    public function coordinatenLabel()
    {
        return 'RD coordinaten';
    }

    public function getLatitude()
    {
        if (!isset($this->latitude) ||
            !isset($this->longitude)) {
            return;
        }
        $converter = new RDConverter;

        return round($converter->gps2X($this->latitude, $this->longitude));
    }

    public function getLongitude()
    {
        if (!isset($this->latitude) ||
                !isset($this->longitude)) {
            return;
        }
        $converter = new RDConverter;
        return round($converter->gps2Y($this->latitude, $this->longitude));
    }
}
