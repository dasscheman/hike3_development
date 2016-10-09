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
    public $nood_envelop_name;
	public $day_date;
	public $group_name;
	public $route_name;
	public $username;
	public $score;

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
                'message' => Yii::t('app/error', 'This hint is already opened by this group.')]
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
            'opened' => Yii::t('app', 'Opened'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(TblUsers::className(), ['user_ID' => 'create_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelop()
    {
        return $this->hasOne(TblNoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(TblUsers::className(), ['user_ID' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(TblEventNames::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(TblGroups::className(), ['group_ID' => 'group_ID']);
    }

	public function envelopIsOpenedByAnyGroup($nood_envelop_id,
					     $event_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $nood_envelop_id AND
				      event_ID = $event_id AND
				      opened = 1";
		$data = OpenNoodEnvelop::find($criteria);
		if(isset($data->open_nood_envelop_ID))
			{return(true);}
		else
			{return(false);}
	}

	public function isEnvelopOpenByGroup($nood_envelop_id,
					     $event_id,
					     $group_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $nood_envelop_id AND
				      event_ID = $event_id AND
				      group_ID = $group_id AND
				      opened = 1";
		$data = OpenNoodEnvelop::find($criteria);
		if(isset($data->open_nood_envelop_ID))
			{return(true);}
		else
			{return(false);}
	}

	public function getOpenEnvelopScore($event_id,
					    $group_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="event_ID = $event_id AND
				      group_ID = $group_id AND
				      opened = 1";
		$data = OpenNoodEnvelop::findAll($criteria);

        $score = 0;
    	foreach($data as $obj)
        {
            $score = $score + NoodEnvelop::getNoodEnvelopScore($obj->nood_envelop_ID);
        }
        return $score;
	}
}