<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;

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
}
