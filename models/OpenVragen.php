<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_open_vragen".
 *
 * @property integer $open_vragen_ID
 * @property string $open_vragen_name
 * @property integer $event_ID
 * @property integer $route_ID
 * @property integer $vraag_volgorde
 * @property string $omschrijving
 * @property string $vraag
 * @property string $goede_antwoord
 * @property integer $score
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Route $route
 * @property Users $updateUser
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens
 */
class OpenVragen extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_open_vragen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_vragen_name', 'event_ID', 'route_ID', 'omschrijving', 'vraag', 'goede_antwoord', 'score'], 'required'],
            [['event_ID', 'route_ID', 'vraag_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['omschrijving'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['open_vragen_name', 'vraag', 'goede_antwoord'], 'string', 'max' => 255],
            [   
                ['open_vragen_name', 'event_ID', 'route_ID'], 
                'unique', 
                'targetAttribute' => ['open_vragen_name', 'event_ID', 'route_ID'], 
                'message' => Yii::t('app/error', 'Question name already exists for this route section')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'open_vragen_ID' => Yii::t('app', 'Question ID'),
            'open_vragen_name' => Yii::t('app', 'Question Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'route_ID' => Yii::t('app', 'Route ID'),
            'vraag_volgorde' => Yii::t('app', 'Question Order'),
            'omschrijving' => Yii::t('app', 'Description'),
            'vraag' => Yii::t('app', 'Question'),
            'goede_antwoord' => Yii::t('app', 'Correct Answer'),
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
    public function getRoute()
    {
        return $this->hasOne(Route::className(), ['route_ID' => 'route_ID']);
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
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['open_vragen_ID' => 'open_vragen_ID']);
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
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);

		$hikeStatus = EventNames::getStatusHike($event_id);
		$rolPlayer = DeelnemersEvent::getRolOfPlayer($event_id, \Yii::$app->user->id);
		$route_id = OpenVragen::getRouteIdVraag($model_id);

		if ($action_id == 'moveUpDownVraag' and
			$hikeStatus == EventNames::STATUS_opstart and
			$rolPlayer == DeelnemersEvent::ROL_organisatie) {
				$actionAllowed = true;
		}

		if ($action_id == 'createIntroductie' and
			$hikeStatus == EventNames::STATUS_opstart and
			$rolPlayer == DeelnemersEvent::ROL_organisatie) {
				$actionAllowed = true;
		}

		if ($action_id == 'moveUpDown'){
			if (!isset($order) || !isset($route_id)){
				return $actionAllowed;
			}
			if ($hikeStatus != EventNames::STATUS_opstart or
				$rolPlayer != DeelnemersEvent::ROL_organisatie) {
					return $actionAllowed;
			}
			if ($move == 'up') {
				$nextOrderExist = OpenVragen::higherOrderNumberExists($event_id,
																			   $model_id,
																			   $order,
																			   $route_id);
			}
			if ($move == 'down') {
				$nextOrderExist = OpenVragen::lowerOrderNumberExists($event_id,
																			  $model_id,
																			  $order,
																			  $route_id);
			}
			if ($nextOrderExist) {
				$actionAllowed = true;
			}
		}

		return $actionAllowed;
	}

	/**
	 * Returns list van alle beschikbare vragen.
	 */
	public function getOpenVragenIdOptions($event_Id)
	{
		$data = OpenVragen::findAll('event_ID =:event_Id', array(':event_Id' => $event_Id));
			$list = CHtml::listData($data, 'open_vragen_ID', 'open_vragen_name');
		return $list;
	}

	/**
	 *TODO: check of dit de manier en de plek is voor deze dataprovider.
	 */
	public function openVragenAllDataProvider($event_id)
	{
	     $where = "event_ID = $event_id";

	     $dataProvider=new CActiveDataProvider('OpenVragen',
		 array(
		     'criteria'=>array(
			 'condition'=>$where,
			 //'order'=>'binnenkomst DESC',
			 ),
		     'pagination'=>array(
			 'pageSize'=>5,
		     ),
	     ));
	     return $dataProvider;

	 }


	/**
	 * Returns lijst met beschikbare vragenen.
	 */
	public function getOpenAvailableVragen($event_id)
	{
		$data = OpenVragen::findAll('event_ID = $event_id');
		$list = CHtml::listData($data, '$open_vragen_ID', '$open_vragen_name');
		return $list;
	}

	 /**
	  * Returns omschrijving (naam) van een vraag.
	  * TODO: moet niet een list returnen.
	  */
	 public function getOpenVragenName($vraag_id)
	 {
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		return $data->open_vragen_name;
	 }

	public function getOpenVraag($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		return $data->vraag;
	}

	/**
	 * Zelfde als hierboven 1 van de twee moet weg.
	 */
	public function getOpenVraagAntwoord($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		//$list = CHtml::listData($data, 'open_vragen_ID', 'goede_antwoord');
		return $data->goede_antwoord;
	}

	/**
	 * Returns score voor een vraag.
	 */
	public function getOpenVraagScore($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
        return isset($data->score) ?
            $data->score : 0;
	}

	/**
	 * Returns volgnummer van een vraag.
	 */
	public function	getVraagVolgorde($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		return $data->vraag_volgorde;
	}

	/**
	 * Returns Dag van een vraag.
	 */
	public function	getVraagDag($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));

		if(isset($data->route_ID))
		{
			$date = Route::getDayOfRouteId($data->route_ID);
			return $date;
		}
		else
			{return;}
	}

	/**
	 * Returns Route onderdeel van vraag
	 */
	public function	getRouteOnderdeelVraag($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		$day = Route::getRouteName($data->route_ID);
		return $day;
	}

	/**
	 * Returns Route ID van vraag
	 */
	public function	getRouteIdVraag($vraag_id)
	{
		$data = OpenVragen::find('open_vragen_ID =:vraag_id',
						  array(':vraag_id' => $vraag_id));
		if(isset($data->route_ID)){
			return $data->route_ID;
		} else {
			return false;
		}
	}

	public function getNewOrderForIntroductieVragen($event_id)
	{
        $route_id = Route::getIntroductieRouteId($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);
		$criteria->order = "vraag_volgorde DESC";
		$criteria->limit = 1;

		if (OpenVragen::exists($criteria))
		{	$data = OpenVragen::findAll($criteria);
			$newOrder = $data[0]->vraag_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}


	public function getNewOrderForVragen($event_id, $route_id)
	{
        $criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);
		$criteria->order = "vraag_volgorde DESC";
		$criteria->limit = 1;

		if (OpenVragen::exists($criteria))
		{	$data = OpenVragen::findAll($criteria);
			$newOrder = $data[0]->vraag_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}

	public function getNumberVragenRouteId($event_id, $route_id)
	{
        $criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);

		return OpenVragen::count($criteria);
	}

	public function lowerOrderNumberExists($event_id, $id, $vraag_order, $route_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND open_vragen_ID !=:id AND route_ID=:route_id AND vraag_volgorde >=:order';
		$criteria->params=array(':event_id' => $event_id,
								':id' => $id,
								':route_id' => $route_id,
								':order' => $vraag_order);

		if (OpenVragen::exists($criteria)) {
			return true;
		} else {
			return false;
		}
	}

	public function higherOrderNumberExists($event_id, $id, $vraag_order, $route_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND open_vragen_ID !=:id AND route_ID =:route_id AND vraag_volgorde <=:order';
		$criteria->params=array(':event_id' => $event_id,
								':id' => $id,
								':route_id' => $route_id,
								':order' => $vraag_order);

		if (OpenVragen::exists($criteria)) {
			return true;
		} else {
			return false;
		}
	}
}