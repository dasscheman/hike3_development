<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_qr_check".
 *
 * @property integer $qr_check_ID
 * @property integer $qr_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Qr $qr
 * @property Users $updateUser
 */
class QrCheck extends HikeActiveRecord
{
    public $group_name;
	public $qr_name;
	public $score;
	public $username;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_qr_check';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qr_ID', 'event_ID', 'group_ID'], 'required'],
            [['qr_ID', 'event_ID', 'group_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [
                ['qr_ID', 'group_ID'], 
                'unique', 
                'targetAttribute' => ['qr_ID', 'group_ID'], 
                'message' => Yii::t('app/error', 'Qr code is already checked by this group.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qr_check_ID' => Yii::t('app', 'Qr Check ID'),
            'qr_ID' => Yii::t('app', 'Qr ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
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
    public function getQr()
    {
        return $this->hasOne(Qr::className(), ['qr_ID' => 'qr_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }


	/**
	 * Check if actions are allowed. These checks are not only use in the controllers,
	 * but also for the visability of the menu items.
	 */

    function isActionAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);

		$hikeStatus = EventNames::getStatusHike($event_id);
		$rolPlayer = DeelnemersEvent::getRolOfPlayer($event_id, \Yii::$app->user->id);
		return $actionAllowed;
	}

    /**
     * Checks if qr id is used by any group.
     */
    public function isQrUsed($qr_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="qr_ID =$qr_id";
		return QrCheck::exists($criteria);
	}

	public function existQrCodeForGroup($event_id, $qr_id, $groupPlayer)
	{
		$criteria = new CDbCriteria;
		$criteria->select='qr_check_ID as qr_check_ID';
		//$criteria->select='score as score';                           //Aangepast voor hike
		$criteria->condition="event_ID = $event_id AND
                              qr_ID =$qr_id AND
                              group_ID = $groupPlayer";
		$data = QrCheck::find($criteria);
		if(isset($data->qr_check_ID))
			{ return(true);}
		else
			{ return(false);}
	}

	/**
	 * Returns de score voor het checken van de stillen posten voor een groep
	 */
	public function getQrScore($event_id, $group_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="event_ID = $event_id AND
				      group_ID = $group_id";
		$data = QrCheck::findAll($criteria);

        $score = 0;
    	foreach($data as $obj)
        {
            $score = $score + Qr::getQrScore($obj->qr_ID);
        }
        return $score;
	}
}