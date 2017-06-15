<?php

namespace app\models;

use Yii;
use yii\db\Query;
use app\models\ArrayHelper;
/**
 * This is the model class for table "tbl_friend_list".
 *
 * @property integer $friend_list_ID
 * @property integer $user_ID
 * @property integer $friends_with_user_ID
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property Users $friendsWithUser
 * @property Users $updateUser
 * @property Users $user
 */
class FriendList extends HikeActiveRecord
{
	const STATUS_pending=0;
	const STATUS_waiting=1;
	const STATUS_accepted=2;
	const STATUS_declined=3;
	const STATUS_canceled=4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_friend_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_ID', 'friends_with_user_ID', 'status', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [
                ['user_ID', 'friends_with_user_ID'],
                'unique',
                'targetAttribute' => ['user_ID', 'friends_with_user_ID'],
                'message' => Yii::t('app', 'This user is already you friend, has an invitation from you or is blocked.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'friend_list_ID' => Yii::t('app', 'Friend List ID'),
            'user_ID' => Yii::t('app', 'User ID'),
            'friends_with_user_ID' => Yii::t('app', 'Friends With User ID'),
            'status' => Yii::t('app', 'Status'),
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
    public function getFriendsWithUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'friends_with_user_ID']);
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
	* Retrieves a list of statussen
	* @return array an array of available statussen.
	*/
	public function getStatusOptions()
	{
		return array(
			self::STATUS_pending=>'Wachten op Reactie',
			self::STATUS_waiting=>'Wachten op Acceptatie',
			self::STATUS_accepted=>'Vrienden',
			self::STATUS_declined=>'Afgewezen',
			self::STATUS_canceled=>'Ontvriend',
			);
	}

	/**
	* @return string the status text display
	*/
	public function getStatusText()
	{
		$statusOptions=$this->statusOptions;
		return isset($statusOptions[$this->status]) ?
			$statusOptions[$this->status] : "unknown status ({$this->status})";
	}

       	/**
	* @return string the status text display
	*/
	public function getStatusText2($status)
	{
		$statusOptions=$this->statusOptions;
		return isset($statusOptions[$status]) ?
			$statusOptions[$status] : "unknown status ({$status})";
	}

   	/**
	* Retrieves a list of users
	* @return array an array of all available users'.
	*/

	public function getFriendNameOptions()
	{
		$criteria=new CDbCriteria();
		//Bestaande vrienden worden uitgefilterd uit de user lijst.
		// de huidige gebruiker wordt er ook uitgefilterd.
		$criteria->addCondition("t.user_ID IN ( SELECT friends_with_user_ID
								FROM `tbl_friend_list`
								WHERE user_ID =:currentuser AND status =2)
                                AND t.user_ID <>:currentuser");
		$criteria->order = username;
		$criteria->params = array(':currentuser'=>Yii::app()->user->id);

		Yii::app()->user->id;
		$data = Users::model()->findAll($criteria);

		foreach($data as $m)
		{
			$results[] = array("id"=>$m->user_ID, "label"=>$m->username);
		}
		return $results;
	}

       	/**
	* Retrieves a list of users
	* @return array an array of all available users'.
	*/

	public function getFriendNames()
	{
        $sql = 'SELECT tbl_users.user_ID IN (SELECT friends_with_user_ID
                FROM `tbl_friend_list`
                WHERE tbl_friend_list.user_ID =' . Yii::$app->user->id . ' AND tbl_friend_list.status =2)
                FROM tbl_users
                WHERE tbl_users.user_ID <>' . Yii::$app->user->id;
        $model = Users::findBySql($sql)->all();

		foreach($model as $m)
		{
			$results[] = array("id"=>$m->user_ID, "label"=>$m->username);
		}
		return $results;
	}

    /**
	* Retrieves a list of users
	* @return array an of available friendusers which are not subscribed to current event.'.
    * When the group_id is set, the users in this group will be included.
	*/
	public function getFriendsForEvent($group_id = NULL)
	{
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_accepted])
                        ->addParams([':user_id' => Yii::$app->user->id]);

        $queryDeelnemersEvent = DeelnemersEvent::find();

        if ($group_id) {
            $queryDeelnemersEvent->select('user_ID')
                ->where('event_ID=:event_id and (group_ID!=:group_id or rol!=:rol)')
                ->addParams(
                    [
                        ':event_id' => Yii::$app->user->identity->selected_event_ID,
                        ':group_id' => $group_id,
                        ':rol' => DeelnemersEvent::ROL_deelnemer,
                    ]);
        } else {
            $queryDeelnemersEvent->select('user_ID')
                ->where('event_ID=:event_id')
                ->addParams([':event_id' => Yii::$app->user->identity->selected_event_ID]);
        }

        $result = Users::find()
            ->where(['in', 'tbl_users.user_ID', $queryFriendList])
            ->andwhere(['not in', 'tbl_users.user_ID', $queryDeelnemersEvent])
            ->all();

        $arrayRestuls = \yii\helpers\ArrayHelper::map($result, 'user_ID', 'fullName');
        return $arrayRestuls;
	}
}
