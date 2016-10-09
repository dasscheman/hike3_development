<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_post_passage".
 *
 * @property integer $posten_passage_ID
 * @property integer $post_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property integer $gepasseerd
 * @property string $binnenkomst
 * @property string $vertrek
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property TblUsers $createUser
 * @property TblEventNames $event
 * @property TblGroups $group
 * @property TblPosten $post
 * @property TblUsers $updateUser
 */
class PostPassage extends HikeActiveRecord
{
  	public $group_name;
	public $post_name;
	public $date;
	public $score;
	public $username;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_post_passage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_ID', 'event_ID', 'group_ID', 'gepasseerd'], 'required'],
            [['post_ID', 'event_ID', 'group_ID', 'gepasseerd', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['binnenkomst', 'vertrek', 'create_time', 'update_time'], 'safe'],
            [   
                ['post_ID', 'event_ID', 'group_ID'], 
                'unique', 
                'targetAttribute' => ['post_ID', 'event_ID', 'group_ID'], 
                'message' => Yii::t('app/error', 'This group already checked in on this station.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posten_passage_ID' => Yii::t('app', 'Checked Station ID'),
            'post_ID' => Yii::t('app', 'Station ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'gepasseerd' => Yii::t('app', 'Passed'),
            'binnenkomst' => Yii::t('app', 'Arrival'),
            'vertrek' => Yii::t('app', 'Departure'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(TblPosten::className(), ['post_ID' => 'post_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(TblUsers::className(), ['user_ID' => 'update_user_ID']);
    }

	/**
	 * De velden score en gepasseerd worden gezet als er een nieuwe record aangemaakt wordt.
	 */
	public function beforeSave($insert)
    {
		if(parent::beforeSave($insert))
		{
			if($this->isNewRecord)
			{
				$this->gepasseerd = 1;
			}
			return true;
		}
		else
			return false;
    }
	
	public function postPassageGroupDataProvider($event_id, $group_id)
	{
	    $where = "event_ID = $event_id AND group_ID = $group_id";
    
	    $dataProvider=new CActiveDataProvider('PostPassage',
		array(
		    'criteria'=>array(
			'condition'=>$where,
			'order'=>'post_ID DESC',
			),
		    'pagination'=>array(
			'pageSize'=>5,
		    ),
	    ));
	    return $dataProvider;
      
	}
      
	public function postPassageAllDataProvider($event_id)
	{
	    $where = "event_ID = $event_id";
    
	    $dataProvider=new CActiveDataProvider('PostPassage',
		array(
		    'criteria'=>array(
			'condition'=>$where,
			'order'=>'binnenkomst DESC',
			),
		    'pagination'=>array(
			'pageSize'=>5,
		    ),
	    ));
	    return $dataProvider;
      
	}
	
	/**
	 * Returns de tijd van de laatste post passage van een groep
	 * Als groep geen enkele post is gepasserd return = nvt
	 */
	public function getLaatstePostPassageTijd($event_id, $group_id)
	{
		if(!isset($group_id))
		{
			return('Geen groepsgegevens');
		};
		
		$criteria=new CDbCriteria;
		$criteria->select = 'binnenkomst';  
		$criteria->condition = 'event_ID=:event_id AND group_ID=:group_id';
		$criteria->order =  'binnenkomst DESC';
		$criteria->params=array(':event_id'=>$event_id, 
					':group_id' => $group_id);
		$data=PostPassage::find($criteria); 
	
		if(isset($data->binnenkomst))
			{ return($data->binnenkomst);}
		else
			{ return('nvt');}
	}
	
	/**
	 * Returns de post naam van de laatste post passage van een groep
	 * Als groep geen enkele post is gepasserd return = nvt
	 */
	public function getLaatstePostPassageNaam($event_id, $group_id)
	{
		if(!isset($group_id))
		{
			return('Geen groepsgegevens');
		};
		
		$criteria=new CDbCriteria;
		$criteria->select = 'post_ID';  
		$criteria->condition = 'event_ID=:event_id AND group_ID=:group_id';
		$criteria->order =  'binnenkomst DESC';
		$criteria->params=array(':event_id'=>$event_id,
					':group_id' => $group_id);
		$data=PostPassage::find($criteria); 
	
		if(isset($data->post_ID))
			{ return($data->post_ID);}
		else
			{ return('nvt');}
	}
	
	/**
	 * Returns de score voor het passeren van de posten voor een groep
	 */
	public function getPostScore($event_id, $group_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="event_ID = $event_id AND
				      group_ID = $group_id AND
				      gepasseerd = 1";
		$data = PostPassage::findAll($criteria);

        $score = 0;
    	foreach($data as $obj)
        {
            $score = $score + Posten::getPostScore($obj->post_ID);
        }
        return $score;
	}
	
	public function isTimeLeftToday($event_id, $group_id)
	{
		if (PostPassage::timeLeftToday($event_id, $group_id) > 0)
			return true;

		return false;
	}

	public function walkingTimeToday($event_id, $group_id)
	{
		$criteriaEvent = new CDbCriteria;
		$criteriaEvent->condition = 'event_ID = :event_id';
		$criteriaEvent->params = array(':event_id'=>$event_id);
		$dataEvent = EventNames::find($criteriaEvent);
	
		$criteriaPostenPassages = new CDbCriteria;
		$criteriaPostenPassages->with = array('post');

		$criteriaPostenPassages->condition = 'group_ID =:group_id AND
											  post.event_ID =:event_id AND
											  post.date =:active_date';
		$criteriaPostenPassages->order = 'binnenkomst ASC';
		$criteriaPostenPassages->params = array(':group_id'=>$group_id,
												':event_id'=>$event_id,
												':active_date'=>$dataEvent->active_day);
		$dataPostPassages = PostPassage::findAll($criteriaPostenPassages);	
		$aantalPosten = PostPassage::count($criteriaPostenPassages);	

		$totalTime = 0;
		$timeLastStint = 0;
		$timeLeftLastPost = 0;
		$atPost = false;
		$count = 1;

		foreach($dataPostPassages as $obj)
		{
			if ($aantalPosten == 1 && (strtotime($obj->vertrek))) {
				// Als $aantalPosten 1 is dan is het de start post en moeten 
				// we alleen naar de vertrektijd gebruiken.
				// De deelnemers zijn niet op een post, dus ze zijn nog aan het lopen.
				// Daarom moet de huidige tijd min de laatste vertrektijd van elkaar 
				// afgetrokken worden en opgeteld worden bij totaltime.
				$timeLastStint = strtotime(date('Y-m-d H:i:s')) - strtotime($obj->vertrek);		
				$totalTime = $totalTime + $timeLastStint;
			}
			
			if ($count > 1) {
				$to_time = strtotime($obj->binnenkomst);
				$from_time = strtotime($timeLeftLastPost);
				$timeLastStint = $to_time-$from_time;
				$totalTime = $totalTime + $timeLastStint;

				if ($count == $aantalPosten && (strtotime($obj->vertrek))) {

					// Hier wordt de laatste post gecontroleerd.
					// De deelnemers zijn niet op een post, dus ze zijn nog aan het lopen.
					// Daarom moet de huidige tijd min de laatste vertrektijd van elkaar 
					// afgetrokken worden en opgeteld worden bij totaltime.
					$timeLastStint = strtotime(date('Y-m-d H:i:s')) - strtotime($obj->vertrek);		
					$totalTime = $totalTime + $timeLastStint;
				}
			}

			$timeLeftLastPost = $obj->vertrek;
			$count++;
        }
		return $totalTime;
	}

	public function timeLeftToday($event_id, $group_id)
	{
		$criteriaEvent = new CDbCriteria;
		$criteriaEvent->condition = 'event_ID = :event_id';
		$criteriaEvent->params = array(':event_id'=>$event_id);
		$dataEvent = EventNames::find($criteriaEvent);

		$totalTime = PostPassage::walkingTimeToday($event_id, $group_id);
		if ((strtotime("1970-01-01 $dataEvent->max_time UTC") - $totalTime) < 0 ) {
			return 0;
		}
		return strtotime("1970-01-01 $dataEvent->max_time UTC") - $totalTime;
	}

	public function convertToHoursMinute($timestamp)
	{
		$time = sprintf('%02d',floor($timestamp / 60 / 60))  . ':' . sprintf('%02d',($timestamp / 60) %60);
		return $time;
	}

	public function displayWalkingTime($event_id, $group_id)
	{
		$criteriaEvent = new CDbCriteria;
		$criteriaEvent->condition = 'event_ID = :event_id';
		$criteriaEvent->params = array(':event_id'=>$event_id);
		$dataEvent = EventNames::find($criteriaEvent);
		
		$criteriaPostenPassages = new CDbCriteria;
		$criteriaPostenPassages->with = array('post');

		$criteriaPostenPassages->condition = 'group_ID =:group_id AND
											  post.event_ID =:event_id AND
											  post.date =:active_date';

		if ($dataEvent->active_day == null || $dataEvent->active_day == '0000-00-00') {
			return 'Geen dag geactiveerd';
		}	
		$criteriaPostenPassages->order = 'binnenkomst ASC';
		$criteriaPostenPassages->params = array(':group_id'=>$group_id,
												':event_id'=>$event_id,
												':active_date'=>$dataEvent->active_day);
		$aantalPosten = PostPassage::count($criteriaPostenPassages);	

		if ($aantalPosten == 0){
			return 'nog niet gestart';
		}

		if ($this->timeLeftToday($event_id, $group_id) == 0){
			return $this->convertToHoursMinute(strtotime("1970-01-01 $dataEvent->max_time UTC"));
		}		

		return $this->convertToHoursMinute($this->walkingTimeToday($event_id, $group_id));
	}

	public function displayTimeLeft($event_id, $group_id)
	{
		$criteriaEvent = new CDbCriteria;
		$criteriaEvent->condition = 'event_ID = :event_id';
		$criteriaEvent->params = array(':event_id'=>$event_id);
		$dataEvent = EventNames::find($criteriaEvent);
		if ($dataEvent->max_time == null || $dataEvent->max_time == '00:00:00') {
			return 'Er is geen maximum tijd voor vandaag';
		}
		return $this->convertToHoursMinute($this->timeLeftToday($event_id, $group_id));
	}

	public function isFirstPostOfDayForGroup($event_id, $group_id)
	{
		$date = EventNames::model()->getActiveDayOfHike($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde DESC";
		$dataPosten = Posten::findAll($criteria);
		
    	foreach($dataPosten as $obj)
        {
            $criteria = new CDbCriteria();
			$criteria->condition = 'event_ID =:event_id AND post_ID =:post_id AND group_ID =:group_id';
			$criteria->params=array(':event_id' => $event_id, ':post_id' =>$obj->post_ID, ':group_id' =>$group_id);
			$dataPostenPassage = PostPassage::find($criteria);

			if (isset($dataPostenPassage->posten_passage_ID)) {
				return false;
			}
        }
		return true;
	}

	public function notAllPostsOfDayPassedByGroup($event_id, $group_id)
	{
		$date = EventNames::getActiveDayOfHike($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde DESC";
		$dataPosten = Posten::findAll($criteria);

    	foreach($dataPosten as $obj)
        {
            $criteria = new CDbCriteria();
			$criteria->condition = 'event_ID =:event_id AND post_ID =:post_id AND group_ID =:group_id';
			$criteria->params=array(':event_id' => $event_id, ':post_id' =>$obj->post_ID, ':group_id' =>$group_id);
			$dataPostenPassage = PostPassage::find($criteria);
			if (!isset($dataPostenPassage->posten_passage_ID)) {
				return true;
			}
        }
		return false;
	}
}