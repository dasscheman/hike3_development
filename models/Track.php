<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_track".
 *
 * @property int $track_ID
 * @property int $event_ID
 * @property int $group_ID
 * @property int $user_ID
 * @property string $latitude
 * @property string $longitude
 * @property int $accuracy
 * @property int $timestamp
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Users $updateUser
 * @property Users $user
 */
class Track extends HikeActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_track';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_ID', 'user_ID'], 'required'],
            [['event_ID', 'group_ID', 'user_ID', 'accuracy', 'timestamp', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['create_time', 'update_time'], 'safe'],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['group_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Groups::className(), 'targetAttribute' => ['group_ID' => 'group_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
            [['user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_ID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'track_ID' => Yii::t('app', 'Track  ID'),
            'event_ID' => Yii::t('app', 'Event  ID'),
            'group_ID' => Yii::t('app', 'Group  ID'),
            'user_ID' => Yii::t('app', 'User  ID'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
            'accuracy' => Yii::t('app', 'Accuracy'),
            'timestamp' => Yii::t('app', 'Timestamp'),
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
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupName()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(Groups::className(), ['group_ID' => 'group_ID'])->one()->group_name;
        });
        return $data;
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
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserName()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(Users::className(), ['id' => 'user_ID'])->one()->voornaam;
        });
        return $data;
    }

    public function checkInterval()
    {
        $model = Track::find()
            ->select('timestamp')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->orderBy(['timestamp' => SORT_DESC]);

        if (!$model->exists()) {
            return true;
        }

        $time_diff = time() - $model->one()->timestamp;

        if ($time_diff > 60 * 5) {
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorUserForEvent()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            $deelnemer = DeelnemersEvent::find()
                ->where('event_ID =:event_id AND user_ID =:user_id')
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':user_id' => $this->user_ID
                ]);

            if ($deelnemer->exists() && $deelnemer->one()->color !== null) {
                return $deelnemer->one()->color;
            }
            return false;
        });
        return $data;
    }
}
