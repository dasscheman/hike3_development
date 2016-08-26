<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_route".
 *
 * @property integer $route_ID
 * @property string $route_name
 * @property integer $event_ID
 * @property string $day_date
 * @property integer $route_volgorde
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property NoodEnvelop[] $NoodEnvelops
 * @property OpenVragen[] $OpenVragens
 * @property Qr[] $tblQrs
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 */
class Route extends HikeActiveRecord
{	
    public $routeMoveUpAllowed = false;
	public $routeMoveDownAllowed = false;
	private $_activeTab;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_route';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_name', 'event_ID'], 'required'],
            [['event_ID', 'route_volgorde', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['day_date', 'create_time', 'update_time'], 'safe'],
            [['route_name'], 'string', 'max' => 255],
            [
                ['event_ID', 'day_date', 'route_name'], 
                'unique', 
                'targetAttribute' => ['event_ID', 'day_date', 'route_name'], 
                'message' => Yii::t('app', 'Route  name already exists for this day.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'route_ID' => Yii::t('app', 'Route ID'),
            'route_name' => Yii::t('app', 'Route Name'),
            'event_ID' => Yii::t('app', 'Event ID'),
            'day_date' => Yii::t('app', 'Day'),
            'route_volgorde' => Yii::t('app', 'Route Order'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['route_ID' => 'route_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens()
    {
        return $this->hasMany(OpenVragen::className(), ['route_ID' => 'route_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs()
    {
        return $this->hasMany(Qr::className(), ['route_ID' => 'route_ID']);
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
        if (isset($_GET['event_id'])) {
            $event_id = $_GET['event_id'];
        }

        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer($event_id, \Yii::$app->user->id);

		if ($action_id == 'moveUpDown'){
			if (!isset($date) || !isset($move)){
				return $actionAllowed;
			}
			if ($hikeStatus != EventNames::STATUS_opstart or
				$rolPlayer != DeelnemersEvent::ROL_organisatie) {
					return $actionAllowed;
			}
			if ($move == 'up'){
				$nextOrderExist = Route::higherOrderNumberExists($event_id,
																		  $date,
																		  $order);
			}
			if ($move == 'down'){
				$nextOrderExist = Route::lowererOrderNumberExists($event_id,
																		   $date,
																		   $order);
			}
			if ($nextOrderExist) {
				$actionAllowed = true;
			}
		}

		if ($action_id == 'viewIntroductie'){
            if ($rolPlayer == DeelnemersEvent::ROL_organisatie ){
                $actionAllowed = true;
            }
        }
		return $actionAllowed;
	}

	public function getDayOfRouteId($id)
	{
		$data = Route::find('route_ID =:route_id', array(':route_id' => $id));
		return $data->day_date;
	}
    
	public function getRouteName($id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'route_ID =:id';
		$criteria->params=array(':id' => $id);

		if (Route::exists($criteria))
		{	$data = Route::find($criteria);
			return $data->route_name;
		} else {
			return "nvt";}
	}

	public function getIntroductieRouteId($event_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_name =:route_name';
		$criteria->params=array(':event_id' => $event_id, ':route_name' =>'Introductie');
		$criteria->order = "route_volgorde DESC";
		$criteria->limit = 1;

		if (Route::exists($criteria))
		{
			$data = Route::findAll($criteria);
			$introductieID = $data[0]->route_ID;
		} else {
			$introductieID = 1;}

		return $introductieID;
	}

	public function routeIdIntroduction($route_id)
	{
		$data = Route::findByPk($route_id);
		if ($data->route_name == "Introductie")
		{
			return true;
		}
		return false;
	}

	public function getNewOrderForIntroductieRoute($event_id)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_name =:route_name';
		$criteria->params=array(':event_id' => $event_id, ':route_name' =>'introductie');
		$criteria->order = "route_volgorde DESC";
		$criteria->limit = 1;

		if (Route::exists($criteria))
		{	$data = Route::findAll($criteria);
			$newOrder = $data[0]->route_volgorde+1;
		}
		else
		{
				$newOrder = 1;
		}
		return $newOrder;
	}

	public function getNewOrderForDateRoute($event_id, $date)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND day_date =:date AND route_name !=:route_name';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':route_name' =>'introductie');
		$criteria->order = "route_volgorde DESC";
		$criteria->limit = 1;

		if (Route::exists($criteria))
		{	$data = Route::findAll($criteria);
			$newOrder = $data[0]->route_volgorde+1;
		}
		else
		{
				$newOrder = 1;
		}
		return $newOrder;
	}

	public function lowererOrderNumberExists($event_id,
                                        $date,
                                        $route_order)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND day_date =:date AND route_volgorde >:order';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $route_order);

		if (Route::exists($criteria))
			return true;
		else
			return false;
	}

	public function higherOrderNumberExists($event_id,
                                        $date,
                                        $route_order)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND day_date =:date AND route_volgorde <:order';
		$criteria->params=array(':event_id' => $event_id, ':date' => $date, ':order' => $route_order);

		if (Route::exists($criteria))
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