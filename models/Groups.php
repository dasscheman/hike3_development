<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
	private $_rank;
    private $_time_walking;
	private $_time_left;
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
            'bonus_score' => Yii::t('app', 'Bonus Score'),
            'hint_score' => Yii::t('app', 'Hint Penalty')
        ];
    }


    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->event_ID = Yii::$app->user->identity->selected_event_ID;
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
    public function getBonus_score()
    {
        return $this->hasMany(Bonuspunten::className(), ['group_ID' => 'group_ID'])
            ->sum('score');
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

    public function getHint_score()
    {
        return $this->hasOne(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID'])
            ->via('openNoodEnvelops')
            ->sum('score');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['group_ID' => 'group_ID']);
    }

	public function getCorrectOpenVragenAntwoordens()
	{
	   return $this->hasMany(OpenVragenAntwoorden::className(), ['group_ID' => 'group_ID'])
		   ->where([
			   'tbl_open_vragen_antwoorden.correct' => TRUE,
			   'tbl_open_vragen_antwoorden.checked' => TRUE
		   ]);
	}

    public function getVraag()
    {
        return $this->hasOne(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID'])
            ->via('openVragenAntwoordens');
    }

    public function getVragen_score()
    {
        return $this->hasOne(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID'])
            ->via('correctOpenVragenAntwoordens')
            ->sum('score');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['group_ID' => 'group_ID']);
    }

    public function getPost_score()
    {
        return $this->hasOne(Posten::className(), ['post_ID' => 'post_ID'])
            ->via('postPassages')
            ->sum('score');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['group_ID' => 'group_ID']);
    }

    public function getQr_score()
    {
        return $this->hasOne(Qr::className(), ['qr_ID' => 'qr_ID'])
            ->via('qrChecks')
            ->sum('score');
    }

    public function getTotal_score()
    {
        return $this->bonus_score + $this->post_score +
            $this->qr_score + $this->vragen_score -
            $this->hint_score;

    }

	/**
	 * Get al available group name options
	 */
	 // Dit slaat nergens op om een lijst met alle groepen te maken.
	 // Voot alle hikes.
	// public function getGroupOptions()
	// {
	// 	$data = Groups::findAll();
	// 	$groupsArray = CHtml::listData($data, 'group_ID', 'group_name');
	// 	return $groupsArray;
	// }

	/**
	 * Get al available group name options for a particular event.
	 */
	public function getGroupOptionsForEvent()
	{
		$event_id = Yii::$app->user->identity->selected_event_ID;
		$data = Groups::find()
			->where('event_ID =:event_ID')
			->addParams([':event_ID' => $event_id])
			->all();

		$listData=ArrayHelper::map($data,'group_ID','group_name');

		return $listData;
	}

	/**
	* Get group name.
	*/
	public function getGroupName($group_Id)
	{
        dd('NOG NODIG ?');
	    $data = Groups::find('group_ID =:group_Id', array(':group_Id' => $group_Id));

	    return isset($data->group_name) ?
		$data->group_name : "";
	}

    /**
	 * set scores van een group.
	 */
    public function getLast_post_time()
	{
		$this->_last_post_time = PostPassage::getTimeLastPostPassage($this->group_ID);
        return $this->_last_post_time;
	}

    /**
	 * set scores van een group.
	 */
    public function getTime_left()
	{
		$this->_time_left = PostPassage::getTimeLeftToday($this->group_ID);
        return $this->_time_left;
	}

    /**
	 * set scores van een group.
	 */
    public function getTime_walking()
	{
		$this->_time_walking = PostPassage::getWalkingTimeToday($this->group_ID);
        return $this->_time_walking;
	}

	public function getRank()
	{
		$counter = 0;
		$temp_score = 0;

		$data = Groups::find()
            ->where('event_ID =:event_ID')
            ->params([':event_ID' => Yii::$app->user->identity->selected_event_ID])
            ->all();

		foreach($data as $item)
		{
			$groupsArray[$item->group_ID] = $item->total_score;
		}

		arsort($groupsArray);
		foreach($groupsArray as $key=>$key_value)
		{
			if($key == $this->group_ID)
			{
				if($temp_score == $key_value)
				{
                    $this->_rank = $counter;
                    return $this->_rank;
				}
				$temp_score = $key_value;
				$counter++;
                $this->_rank = $counter;
                return $this->_rank;
			}
			if($temp_score != $key_value)
			{
				$counter++;
			}
			$temp_score = $key_value;
		}
	}

    public function setGroupMembers(){
        foreach ($this->deelnemersEvents as $item) {
            if (!isset($this->group_members)) {
                $this->group_members =  $item->user->username;
            } else {
                $this->group_members .=  ', ' . $item->user->username;
            }
        }
    }

    public function addMembersToGroup($group, $members = [])
    {
        if (!$members) {
            return TRUE;
        }
        foreach ($members as $player) {
			$sendmail = FALSE;
            // First check if we can find this user in
            $inschrijving = DeelnemersEvent::find()
                ->where(['user_ID' => $player])
                ->andWhere(['event_ID' => Yii::$app->user->identity->selected_event_ID])
                ->one();

            if (!$inschrijving) {
                //inschrijving bestaat niet dus we maken een nieuwe aan:
                $inschrijving = new DeelnemersEvent;
				$inschrijving->event_ID = Yii::$app->user->identity->selected_event_ID;
				// Deze gebruiker was nog niet toegevoegd aandeze groep,
				// daarom zenden we deze mensen een mail.
				$sendmail = TRUE;
            }
            // Voor nu schrijven we alles over. De aanname is dat in de
            // selectie alleen de juiste namen zichtbaar zijn.
            $inschrijving->group_ID = $group;
            $inschrijving->user_ID = $player;
            $inschrijving->rol = DeelnemersEvent::ROL_deelnemer;
            if (!$inschrijving->save()) {
				foreach ($inschrijving->getErrors() as $error) {
					Yii::$app->session->setFlash('error', Json::encode($error));
				}
                return FALSE;
            }
			if($sendmail) {
				Yii::$app->mailer->compose('sendInschrijving', [
					'mailEventName' => $inschrijving->event->event_name,
					'mailUsersName' => $inschrijving->user->username,
					'mailUsersNameSender' => $inschrijving->createUser->username,
					'mailUsersEmailSender' => $inschrijving->createUser->email,
					'mailRol' => $inschrijving->rol,
					'mailRolText' => DeelnemersEvent::getRolText($inschrijving->rol),
					'mailGroupName' => $inschrijving->group->group_name,
				])
				->setFrom('noreply@biologenkantoor.nl')
				->setTo($inschrijving->user->email)
				->setSubject('Inschrijving Hike')
				->send();
			}
		}
        return TRUE;
    }
}
