<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_deelnemers_event".
 *
 * @property integer $deelnemers_ID
 * @property integer $event_ID
 * @property integer $user_ID
 * @property integer $rol
 * @property integer $group_ID
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Users $updateUser
 * @property Users $user
 */
class DeelnemersEvent extends HikeActiveRecord
{
    const ROL_organisatie=1;
    const ROL_post=2;
    const ROL_deelnemer=3;
    const ROL_toeschouwer=4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_deelnemers_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_ID', 'user_ID'], 'required'],
            [['event_ID', 'user_ID', 'rol', 'group_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['event_ID','create_time', 'update_time'], 'safe'],
            [
                ['event_ID', 'user_ID'],
                'unique',
                'targetAttribute' => ['event_ID', 'user_ID'],
                'message' => Yii::t('app', 'This user is already added to this hike.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deelnemers_ID' => Yii::t('app', 'Player ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'user_ID' => Yii::t('app', 'User ID'),
            'rol' => Yii::t('app', 'Role'),
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'user_ID']);
    }

    /**
     * De het veld event_ID wordt altijd gezet.
     */
     //This doesn't make sense. Deelenemers worden altijd door een ander toegewezen of automatisch.
     // maar nooit door de gebruikr geselecteerde hike.
    // public function beforeValidate() {
    //     if (parent::beforeValidate()) {
    //         $this->event_ID = Yii::$app->user->identity->selected;
    //         return(true);
    //     }
    //     return(false);
    // }

    /**
    * Retrieves een lijst met mogelijke rollen die een deelnemer tijdens een hike kan hebben
    * @return array an array of available rollen.
    */
    public function getRolOptions()
    {
        return array(
            self::ROL_organisatie=>'Organisatie',
            self::ROL_post=>'Post',
            self::ROL_deelnemer=>'Deelnemer',
            self::ROL_toeschouwer=>'Toeschouwer',
        );
    }

    /**
    * Retrieves een lijst met mogelijke rollen die een deelnemer tijdens een hike kan hebben
    * @return array an array of available rollen.
    */
    public function getOrganisationRolOptions()
    {
        return array(
            self::ROL_organisatie=>'Organisatie',
            self::ROL_post=>'Post',
            self::ROL_toeschouwer=>'Toeschouwer',
        );
    }

    /**
    * @return string de rol text display
    */
    public function getRolText($rol)
    {
        $rolOptions = self::getRolOptions();
        return isset($rolOptions[$rol]) ?
            $rolOptions[$rol] : "Onbekende rol";
    }

    /**
    * @return string de rol text display
    */
    public function getRolTextObj()
    {
        $rolOptions=$this->getRolOptions();
        return isset($rolOptions[$this->rol]) ?
            $rolOptions[$this->rol] : "Onbekende rol";
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function getRolOfCurrentPlayerCurrentGame()
    {
        $data = DeelnemersEvent::findOne([
            'event_ID' => Yii::$app->user->identity->selected,
            'user_ID' => Yii::$app->user->identity->id
        ]);

        if(isset($data->rol))
        {
            return $data->rol;
        }
        return FALSE;
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function getRolOfCurrentPlayer($event_id)
    {
        $data = DeelnemersEvent::findOne([
            'event_ID' => $event_id,
            'user_ID' => Yii::$app->user->identity->id
        ]);

        if(isset($data->rol))
        {
            return $data->getRolText($data->rol);
        }
        return FALSE;
    }

    /**
     * @return de group van een speler tijdens een bepaalde hike
     */
    public function getGroupOfPlayer($event_id, $user_id)
    {
        $data = DeelnemersEvent::find()
            ->where('event_ID = :event_Id AND user_ID=:user_Id')
            ->params([':event_Id' => $event_id, ':user_Id' => $user_id])
            ->one();
        if(!isset($data->rol))
        {
            return FALSE;
        }

        if($data->rol <> DeelnemersEvent::ROL_deelnemer ||
           !isset($data->group_ID))
        {
            return FALSE;
        }

        return $data->group_ID;
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function userSignedinInHike($event_Id,
                                        $user_Id)
    {
        $data = DeelnemersEvent::model()->exists('event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id));
        return $data;
    }

    public function getId( $event_Id,
                            $user_Id)
    {
        $data = DeelnemersEvent::model()->find('event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id));
        if(isset($data->deelnemers_ID)) {
            return $data->deelnemers_ID;
        }
        return;
    }

    /**
	* Retrieves a list of users
	* @return array an of available friendusers which are not subscribed to current event.'.
	*/
	public function getFriendsForEvent()
	{
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_accepted])
                        ->addParams([':user_id' => Yii::$app->user->id]);

        $queryDeelnemersEvent = DeelnemersEvent::find();
        $queryDeelnemersEvent->select('user_ID')
            ->where('event_ID=:event_id')
            ->addParams([':event_id' => Yii::$app->user->identity->selected]);


        $result = Users::find()
           // ->select('id,name')->asArray()
            ->where(['in', 'tbl_users.user_ID', $queryFriendList])
            ->andwhere(['not in', 'tbl_users.user_ID', $queryDeelnemersEvent])
            ->all();

        $arrayRestuls = \yii\helpers\ArrayHelper::map($result, 'user_ID', 'username');
        return $arrayRestuls;
	}
}
