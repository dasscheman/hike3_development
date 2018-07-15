<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "tbl_groups".
 *
 * @property int $group_ID
 * @property string $group_name
 * @property int $event_ID
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property DeelnemersEvent[] $DeelnemersEvents
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 * @property NoodEnvelop[] $noodEnvelops
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens
 * @property NoodEnvelop[] $noodEnvelops
 * @property PostPassage[] $PostPassages
 * @property QrCheck[] $QrChecks
 * @property Qr[] $qrs
 * @property TimeTrailCheck[] $TimeTrailChecks
 * @property TimeTrailItem[] $timeTrailItem
 * @property Track[] $Tracks
 */
class Groups extends HikeActiveRecord
{
    public $group_members;
    private $_rank;
    private $_time_walking;
    private $_time_left;
    public $last_post_time;
    public $users_temp;
    public $users_email_temp;

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
            [['event_ID', 'group_name'], 'unique', 'targetAttribute' => ['event_ID', 'group_name']],
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
            'group_ID' => Yii::t('app', 'Group ID'),
            'group_name' => Yii::t('app', 'Group Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
            'bonus_score' => Yii::t('app', 'Bonus'),
            'hint_score' => Yii::t('app', 'Hints'),
            'post_score' => Yii::t('app', 'Posten'),
            'qr_score' => Yii::t('app', 'Stille posten'),
            'vragen_score' => Yii::t('app', 'Vragen'),
            'trail_score' => Yii::t('app', 'Tijdritten'),
            'rank' => Yii::t('app', 'Positie'),
            'total_score' => Yii::t('app', 'Totaal'),
            'time_walking' => Yii::t('app', 'Looptijd'),
            'time_left' => Yii::t('app', 'Te gaan'),
            'users_temp' => Yii::t('app', 'Players'),
            'users_email_temp' => Yii::t('app', 'Players email'),
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
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonus_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasMany(Bonuspunten::className(), ['group_ID' => 'group_ID'])
                ->sum('score');
        });
        return $data;
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
    public function getNoodEnvelop()
    {
        return $this->hasMany(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID'])
            ->viaTable('tbl_open_nood_envelop', ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['group_ID' => 'group_ID']);
    }
      
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID'])
            ->viaTable('tbl_open_nood_envelop', ['group_ID' => 'group_ID']);
    }

    public function getHint_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(NoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID'])
                ->via('openNoodEnvelops')
                ->sum('score');
        });
        return $data;
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
               'tbl_open_vragen_antwoorden.correct' => true,
               'tbl_open_vragen_antwoorden.checked' => true
           ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragen()
    {
        return $this->hasMany(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID'])
            ->via('openVragenAntwoordens');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVraagAwnseredCorrecly()
    {
        return $this->hasMany(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID'])
            ->via('correctOpenVragenAntwoordens');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVragen_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID'])
                ->via('correctOpenVragenAntwoordens')
                ->sum('score');
        });
        return $data;
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
    public function getPosten()
    {
        return $this->hasMany(Posten::className(), ['post_ID' => 'post_ID'])
            ->viaTable('tbl_post_passage', ['group_ID' => 'group_ID']);
    }

    public function getPost_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(Posten::className(), ['post_ID' => 'post_ID'])
                ->via('postPassages')
                ->sum('score');
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQr()
    {
        return $this->hasMany(Qr::className(), ['qr_ID' => 'qr_ID'])
            ->viaTable('tbl_qr_check', ['group_ID' => 'group_ID']);
    }

    public function getQr_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(Qr::className(), ['qr_ID' => 'qr_ID'])
                ->via('qrChecks')
                ->sum('score');
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailChecks()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['group_ID' => 'group_ID']);
    }

    public function getSuccededTimeTrailChecks()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['group_ID' => 'group_ID'])
            ->where(['tbl_time_trail_check.succeded' => true]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItems()
    {
        return $this->hasMany(TimeTrailItem::className(), ['time_trail_item_ID' => 'time_trail_item_ID'])
            ->viaTable('tbl_time_trail_check', ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrail()
    {
        return $this->hasMany(TimeTrail::className(), ['time_trail_ID' => 'time_trail_ID'])
            ->via('timeTrailItems');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrail_score()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasMany(TimeTrailItem::className(), ['time_trail_item_ID' => 'time_trail_item_ID'])
                ->via('succededTimeTrailChecks')
                ->sum('score');
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTracks()
    {
        return $this->hasMany(Track::className(), ['group_ID' => 'group_ID'])
            ->orderBy([
                'timestamp' => SORT_ASC,
                'create_time' => SORT_ASC
            ]);
    }

    public function getTotal_score()
    {
        return $this->bonus_score + $this->post_score + $this->trail_score +
            $this->qr_score + $this->vragen_score - $this->hint_score;
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

        $listData=ArrayHelper::map($data, 'group_ID', 'group_name');

        return $listData;
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
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return Groups::find()
                ->where('event_ID =:event_ID')
                ->params([':event_ID' => Yii::$app->user->identity->selected_event_ID])
                ->all();
        });
        
        foreach ($data as $item) {
            $groupsArray[$item->group_ID] = $item->total_score;
        }

        arsort($groupsArray);
        foreach ($groupsArray as $key=>$key_value) {
            if ($key == $this->group_ID) {
                if ($temp_score == $key_value) {
                    $this->_rank = $counter;
                    return $this->_rank;
                }
                $temp_score = $key_value;
                $counter++;
                $this->_rank = $counter;
                return $this->_rank;
            }
            if ($temp_score != $key_value) {
                $counter++;
            }
            $temp_score = $key_value;
        }
    }

    public function setGroupMembers()
    {
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
            return true;
        }
        foreach ($members as $player) {
            $user = Users::find()
                ->where('id =:player')
                ->params([':player' => $player])
                ->one();

            if (!$user || !$user->addUserToEvent($group, $user->id)) {
                return false;
            }
        }
        return true;
    }

    public function addEmailsToGroup($group, $emails)
    {
        if (!$emails) {
            return true;
        }
        $members  = preg_split("/[\s,]+/", $emails);
        foreach ($members as $player) {
            $user = Users::find()
                ->where('email =:player')
                ->params([':player' => $player])
                ->one();
            $friends = FriendList::find()
                ->where('user_ID =:user_id AND friends_with_user_ID =:friends_with_user_id')
                ->params([
                    ':user_id' => Yii::$app->user->id,
                    ':friends_with_user_id' => $user->id
                    ]);
            if (!$friends->exists()) {
                $model = new FriendList;
                $model->sendRequest($user->id);
            }

            if (!$user || !$user->addUserToEvent($group, $user->id)) {
                return false;
            }
        }
        return true;
    }
}
