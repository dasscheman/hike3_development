<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_open_nood_envelop".
 *
 * @property integer $open_nood_envelop_ID
 * @property integer $nood_envelop_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property integer $opened
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property NoodEnvelop $noodEnvelop
 * @property Users $updateUser
 * @property EventNames $event
 * @property Groups $group
 */
class OpenNoodEnvelop extends HikeActiveRecord
{
    const STATUS_closed = 0;
    const STATUS_open = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_open_nood_envelop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nood_envelop_ID', 'event_ID', 'group_ID'], 'required'],
            [['nood_envelop_ID', 'event_ID', 'group_ID', 'opened', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [
                ['nood_envelop_ID', 'group_ID'],
                'unique',
                'targetAttribute' => ['nood_envelop_ID', 'group_ID'],
                'message' => Yii::t('app', 'This hint is already opened by this group.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'open_nood_envelop_ID' => Yii::t('app', 'Open Hints ID'),
            'nood_envelop_ID' => Yii::t('app', 'Hints ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'opened' => Yii::t('app', 'Geopend'),
            'create_time' => Yii::t('app', 'Geopend op'),
            'create_user_ID' => Yii::t('app', 'Geopend door'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->event_ID = Yii::$app->user->identity->selected_event_ID;
            return(true);
        }
        return(false);
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
    public function getNoodEnvelop()
    {
        return $this->hasOne(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScore()
    {
        return $this->hasOne(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID'])->score;
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
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_closed => Yii::t('app', 'Closed'),
            self::STATUS_open => Yii::t('app', 'Opened'),
        ];
    }


    public function envelopIsOpenedByAnyGroup(
        $nood_envelop_id,
                         $event_id
    ) {
        $criteria = new CDbCriteria;
        $criteria->condition="nood_envelop_ID = $nood_envelop_id AND
				      event_ID = $event_id AND
				      opened = 1";
        $data = OpenNoodEnvelop::find($criteria);
        if (isset($data->open_nood_envelop_ID)) {
            return(true);
        } else {
            return(false);
        }
    }

    public function isEnvelopOpenByGroup(
        $nood_envelop_id,
                         $event_id,
                         $group_id
    ) {
        $criteria = new CDbCriteria;
        $criteria->condition="nood_envelop_ID = $nood_envelop_id AND
				      event_ID = $event_id AND
				      group_ID = $group_id AND
				      opened = 1";
        $data = OpenNoodEnvelop::find($criteria);
        if (isset($data->open_nood_envelop_ID)) {
            return(true);
        } else {
            return(false);
        }
    }

    public function getOpenEnvelopScore($group_id)
    {
        $data = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND opened =:status')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id, ':status' => self::STATUS_open])
            ->all();

        $score = 0;
        foreach ($data as $item) {
            $score = $score + $item->noodEnvelop->score;
        }
        return $score;
    }

    public function anyGroupScoredOpenedHints() {
        return OpenNoodEnvelop::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->exists();
    }
}
