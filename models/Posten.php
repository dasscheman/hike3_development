<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_posten".
 *
 * @property integer $post_ID
 * @property string $post_name
 * @property integer $event_ID
 * @property string $date
 * @property integer $post_volgorde
 * @property integer $score
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property PostPassage[] $PostPassages
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 */
class Posten extends HikeActiveRecord
{
    private $_activeTab;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_posten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_name', 'event_ID'], 'required'],
            [['event_ID', 'post_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['date', 'create_time', 'update_time'], 'safe'],
            [['post_name'], 'string', 'max' => 255],
            [
                ['post_name', 'event_ID', 'date'], 
                'unique', 
                'targetAttribute' => ['post_name', 'event_ID', 'date'], 
                'message' => Yii::t('app/error', 'This station name exist for this day.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_ID' => Yii::t('app', 'Station ID'),
            'post_name' => Yii::t('app', 'Station Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'date' => Yii::t('app', 'Date'),
            'post_volgorde' => Yii::t('app', 'Station Order'),
            'score' => Yii::t('app', 'Score'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['post_ID' => 'post_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['post_ID' => 'post_ID']);
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
	 * Check if actions are allowed. These checks are not only use in the controllers,
	 * but also for the visability of the menu items.
	 */

    function isActionAllowed($controller_id = null,
							 $action_id = null,
							 $event_id = null,
							 $model_id = null,
							 $group_id = null,
							 $date = null,
							 $order = null,
							 $move = null)
    {
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id);

		$hikeStatus = EventNames::getStatusHike($event_id);
		$rolPlayer = DeelnemersEvent::getRolOfPlayer($event_id, \Yii::$app->user->id);

		if ($action_id == 'moveUpDown'){
			if (!isset($date)){
				return $actionAllowed;
			}
			if ($hikeStatus != EventNames::STATUS_opstart or
				$rolPlayer != DeelnemersEvent::ROL_organisatie) {
					return $actionAllowed;
			}
			if ($move == 'up'){
				$nextOrderExist = Posten::higherOrderNumberExists($event_id,
																		   $date,
																		   $order);
			}
			if ($move == 'down'){
				$nextOrderExist = Posten::lowererOrderNumberExists($event_id,
																		   $date,
																		   $order);
			}
			if ($nextOrderExist) {
				$actionAllowed = true;
			}
		}

		return $actionAllowed;
	}

    /**
    * Retrieves a list of post namen
    * @return array an array of all available posten'.
    */
    public function getPostNameOptions($event_Id)
    {
    	$data = Posten::findAll('event_ID =:event_Id', array(':event_Id' => $event_Id));
        $list = CHtml::listData($data, 'post_ID', 'post_name');
        return $list;
    }

    public function getPostNameOptionsToday($event_id)
    {
		$active_day = EventNames::getActiveDayOfHike($event_id);

    	$data = Posten::findAll('event_ID =:event_id AND
										  date =:active_day', array(':event_id' => $event_id,
																    ':active_day' => $active_day));
        $list = CHtml::listData($data, 'post_ID', 'post_name');
        return $list;
    }

    /**
    * Retrieves the score of an post.
    */
    public function getPostScore($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->score) ?
            $data->score : 0;
    }

    /**
    * Haald de post naam op aan de hand van een post ID.
    */
    public function getPostName($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->post_name) ?
            $data->post_name : "nvt";
    }

    public function getDatePost($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->date) ?
            $data->date : "nvt";
    }


	public function getNewOrderForPosten($event_id, $date)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde DESC";
		$criteria->limit = 1;

		if (Posten::exists($criteria))
		{	$data = Posten::findAll($criteria);
			$newOrder = $data[0]->post_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}

	public function lowererOrderNumberExists(   $event_id,
                                                $date,
                                                $post_order)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde >:order';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $post_order);

		if (Posten::exists($criteria)){
			return true;
		} else {
			return false;
		}
	}

	public function higherOrderNumberExists($event_id,
											$date,
											$post_order)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date AND post_volgorde <:order';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $post_order);

		if (Posten::exists($criteria)){
			return true;
		} else {
			return false;
		}
	}

	public function getStartPost($event_id)
	{
		$date = EventNames::getActiveDayOfHike($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde ASC";
		$data = Posten::find($criteria);

		if (isset($data->post_ID))
		{
			return $data->post_ID;
		} else {
			return false;
		}
	}

	public function existPostForActiveDay($event_id)
	{
		$date = EventNames::getActiveDayOfHike($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde ASC";
		$data = Posten::find($criteria);

		if (isset($data->post_ID))
		{
			return true;
		} else {
			return false;
		}
	}

	public function startPostExist($event_id, $date)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date);

		if (Posten::exists($criteria))
			return true;
		else
			return false;
	}

	public function setActiveTab($date)
	{
		$this->_activeTab = $date;
	}

	public function getActiveTab()
	{
		return $this->_activeTab;
	}

	public function getDefaultActiveTab($date)
	{
		if (isset($this->_activeTab))
			return $this->_activeTab;
		else
			return $date;
	}

}