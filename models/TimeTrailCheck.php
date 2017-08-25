<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_time_trail_check".
 *
 * @property integer $time_trail_check_ID
 * @property integer $time_trail_item_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property string $start_time
 * @property string $end_time
 * @property integer $succeded
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property TblUsers $createUser
 * @property TblEventNames $event
 * @property TblGroups $group
 * @property TblTimeTrailItem $timeTrailItem
 * @property TblUsers $updateUser
 */
class TimeTrailCheck extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_time_trail_check';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_item_ID', 'event_ID', 'group_ID'], 'required'],
            [['time_trail_item_ID', 'event_ID', 'group_ID', 'succeded', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['start_time', 'end_time', 'create_time', 'update_time'], 'safe'],
            [['time_trail_item_ID', 'group_ID'], 'unique', 'targetAttribute' => ['time_trail_item_ID', 'group_ID'], 'message' => 'The combination of Time Trail Item  ID and Group  ID has already been taken.'],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'user_ID']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['group_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_ID' => 'group_ID']],
            [['time_trail_item_ID'], 'exist', 'skipOnError' => true, 'targetClass' => TimeTrailItem::className(), 'targetAttribute' => ['time_trail_item_ID' => 'time_trail_item_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'user_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'time_trail_check_ID' => Yii::t('app', 'Time Trail Check  ID'),
            'time_trail_item_ID' => Yii::t('app', 'Time Trail Item  ID'),
            'event_ID' => Yii::t('app', 'Event  ID'),
            'group_ID' => Yii::t('app', 'Group  ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'end_time' => Yii::t('app', 'Endtime Time'),
            'succeded' => Yii::t('app', 'Succeded'),
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
        return $this->hasOne(Users::className(), ['user_ID' => 'create_user_ID']);
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
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItem()
    {
        return $this->hasOne(TimeTrailItem::className(), ['time_trail_item_ID' => 'time_trail_item_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }
}
