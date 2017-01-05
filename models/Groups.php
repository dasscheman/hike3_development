<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_groups".
 *
 * @property integer $group_ID
 * @property string $group_name
 * @property integer $event_ID
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property DeelnemersEvent[] $DeelnemersEvents
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens
 * @property PostPassage[] $PostPassages
 * @property QrCheck[] $QrChecks
 */
class Groups extends HikeActiveRecord
{
	public $group_members;
	public $bonus_score;
	public $post_score;
	public $qr_score;
	public $vragen_score;
	public $hint_score;
	public $totaal_score;
	public $rank;
	public $time_walking;
	public $time_left;
	public $last_post;
	public $last_post_time;
    public $users_temp;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'event_ID'], 'required'],
            [['event_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['group_name'], 'string', 'max' => 255],
            [   ['event_ID', 'group_name'], 
                'unique', 'targetAttribute' => ['event_ID', 'group_name'], 
                'message' => Yii::t('app', 'This group is already assigned to this hike')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_ID' => Yii::t('app', 'Group ID'),
            'group_name' => Yii::t('app', 'Group Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }


    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->event_ID = Yii::$app->user->identity->selected;
            return(true);
        }
        return(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['group_ID' => 'group_ID']);
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['group_ID' => 'group_ID']);
    }

	/**
	 * Get al available group name options
	 */
	public function getGroupOptions()
	{
		$data = Groups::findAll();
		$groupsArray = CHtml::listData($data, 'group_ID', 'group_name');
		return $groupsArray;
	}
	
	/**
	 * Get al available group name options for a particular event.
	 */
	public function getGroupOptionsForEvent($event_ID)
	{
		$data = Groups::findAll('event_ID =:event_ID', array(':event_ID' => $event_ID));
		$groupsArray = CHtml::listData($data, 'group_ID', 'group_name');
		return $groupsArray;
	}
       
	/**
	* Get group name.
	*/
	public function getGroupName($group_Id)
	{
	    $data = Groups::find('group_ID =:group_Id', array(':group_Id' => $group_Id));
	   
	    return isset($data->group_name) ?
		$data->group_name : "";        
	}

	/**
	 * Returns total score van een group.
	 * TODO: deze functie moet naar een generieke plek.
	 */
	public function getTotalScoreGroup(	$event_id,
										$group_id)
	{
		$post_score = PostPassage::getPostScore($event_id, $group_id);
		$qr_score = QrCheck::getQrScore($event_id, $group_id);
		$vragen_score = OpenVragenAntwoorden::getOpenVragenScore($event_id, $group_id); 
		$bonus_score = Bonuspunten::getBonuspuntenScore($event_id, $group_id);
		$OpenEnvelopStrafpunten = OpenNoodEnvelop::getOpenEnvelopScore($event_id, $group_id);

		$total_score = 	$post_score + $qr_score + $vragen_score + $bonus_score - $OpenEnvelopStrafpunten;

		if(isset($total_score))	{
			return($total_score);
		} else {
			return(0);
		}	
	}
		
	public function getRankGroup($event_id,
								 $group_id)
	{
		$counter = 0;
		$temp_score = 0;
		
		$data = Groups::findAll('event_ID =:event_ID',
										 array(':event_ID' => $event_id));
	
		foreach($data as $obj)
		{
			$groupsArray[$obj->group_ID] = Groups::getTotalScoreGroup(
				$event_id,
				$obj->group_ID);
		}
		
		arsort($groupsArray);
		foreach($groupsArray as $key=>$key_value)
		{			
			if($key == $group_id)
			{
				if($temp_score == $key_value)
				{
					return($counter);
				}
				$temp_score = $key_value;
				$counter++;
				return($counter);
			}
			if($temp_score != $key_value)
			{
				$counter++;
			}
			$temp_score = $key_value;
		}
	}

    public function addMembersToGroup($group, $members = []) 
    {
        if (!$members) {
            return TRUE;
        }
        foreach ($members as $player) {
            // First check if we can find this user in
            $inschrijving = DeelnemersEvent::find()
                ->where(['user_ID' => $player])
                ->andWhere(['event_ID' => Yii::$app->user->identity->selected])
                ->one();

            if (!$inschrijving) {
                //inschrijving bestaat niet dus we maken een nieuwe aan:
                $inschrijving = new DeelnemersEvent;
            }
            // Voor nu schrijven we alles over. De aanname is dat in de
            // selectie alleen de juiste namen zichtbaar zijn.
            $inschrijving->group_ID = $group;
            $inschrijving->user_ID = $player;
            $inschrijving->rol = DeelnemersEvent::ROL_deelnemer;
            if (!$inschrijving->save()) {
                return FALSE;
            }
        }
        return TRUE;
    }
}