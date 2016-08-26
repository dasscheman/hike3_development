<?php

namespace app\models;

use Yii;

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
                'message' => Yii::t('app/error', 'This user is already you friend, has an invitation from you or is blocked.')]
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
}


<?php
// Created: 2014
// Modified: 3 jan 2015

/**
 * This is the model class for table "tbl_friend_list".
 *
 * The followings are the available columns in table 'tbl_friend_list':
 * @property integer $friend_list_ID
 * @property integer $user_ID
 * @property integer $friends_with_user_ID
 * @property integer $status
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Users $friendsWithUser
 * @property Users $createUser
 * @property Users $updateUser
 */
class FriendList extends HikeActiveRecord
{
	const STATUS_pending=0;
	const STATUS_waiting=1;
	const STATUS_accepted=2;
	const STATUS_declined=3;
	const STATUS_canceled=4;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FriendList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_friend_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_ID, friends_with_user_ID, status, create_user_ID, update_user_ID', 'numerical', 'integerOnly'=>true),
			array('create_time, update_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('friend_list_ID, user_ID, friends_with_user_ID, status, create_time, create_user_ID, update_time, update_user_ID', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_ID'),
			'friendsWithUser' => array(self::BELONGS_TO, 'Users', 'friends_with_user_ID'),
			'createUser' => array(self::BELONGS_TO, 'Users', 'create_user_ID'),
			'updateUser' => array(self::BELONGS_TO, 'Users', 'update_user_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'friend_list_ID' => 'Friend List',
			'user_ID' => 'User',
			'friends_with_user_ID' => 'Friends With User',
			'status' => 'Status',
			'create_time' => 'Uitgenodigd op',
			'create_user_ID' => 'Create User',
			'update_time' => 'Update Time',
			'update_user_ID' => 'Update User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$user_id = Yii::app()->user->id; 
	    $criteria=new CDbCriteria;
	   
	    $criteria->compare('friend_list_ID',$this->friend_list_ID);
	    $criteria->compare('user_ID',$this->user_ID);
	    $criteria->compare('friends_with_user_ID',$this->friends_with_user_ID);
	    $criteria->compare('status',$this->status);
	    $criteria->compare('create_time',$this->create_time,true);
	    $criteria->compare('create_user_ID',$this->create_user_ID);
	    $criteria->compare('update_time',$this->update_time,true);
	    $criteria->compare('update_user_ID',$this->update_user_ID);
	    return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	    ));
	}

	/**
	 * Check if actions are allowed. These checks are not only use in the controllers,
	 * but also for the visability of the menu items. 
	 */
    function isActionAllowed($controller_id = null, $action_id = null, $event_id = null, $model_id = null, $group_id = null)
    {
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
		$requester_id = $model_id;
		$dataAccepter = FriendList::model()->find('user_ID =:requestUserId AND
										   friends_with_user_ID =:acceptingUserId',
									 array(':requestUserId'=>$requester_id,
										   ':acceptingUserId'=>Yii::app()->user->id));
		
		if (isset($dataAccepter) && 
			$controller_id === 'friendList' && 
			in_array($action_id, array('accept', 'decline'))) {
				$actionAllowed = true;
        }   	
		return $actionAllowed;
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
}