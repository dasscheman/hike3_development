<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_time_trail".
 *
 * @property integer $time_trail_ID
 * @property string $time_trail_name
 * @property integer $event_ID
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property TblUsers $createUser
 * @property TblEventNames $event
 * @property TblUsers $updateUser
 * @property TblTimeTrailItem[] $tblTimeTrailItems
 */
class TimeTrail extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_time_trail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_name', 'event_ID'], 'required'],
            [['event_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['time_trail_name'], 'string', 'max' => 15],
            [['time_trail_name', 'event_ID'], 'unique', 'targetAttribute' => ['time_trail_name', 'event_ID'], 'message' => 'The combination of Time Trail Name and Event  ID has already been taken.'],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'time_trail_ID' => Yii::t('app', 'Time Trail  ID'),
            'time_trail_name' => Yii::t('app', 'Time Trail Name'),
            'event_ID' => Yii::t('app', 'Event  ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User  ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'create_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(EventNames::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItems()
    {
        return $this->hasMany(TimeTrailItem::className(), ['time_trail_ID' => 'time_trail_ID']);
    }
}
