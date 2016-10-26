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
    private $event_ID;
    private $day_date;

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

    public function getEventID()
    {
        return $this->event_ID;
    }

    public function setEventID($value)
    {
        $this->event_ID = $value;
    }

    public function getDayDate()
    {
        return $this->day_date;
    }

    public function setDayDate($value)
    {
        $this->day_date = $value;
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['route_ID' => 'route_ID'])->all();
    }

    public function getNoodEnvelopCount()
    {
        return $this->hasMany(NoodEnvelop::className(), ['route_ID' => 'route_ID'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens()
    {
        return $this->hasMany(OpenVragen::className(), ['route_ID' => 'route_ID'])->all();
    }

    public function getOpenVragenCount()
    {
        return $this->hasMany(OpenVragen::className(), ['route_ID' => 'route_ID'])->count();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs()
    {
        return $this->hasMany(Qr::className(), ['route_ID' => 'route_ID'])->all();
    }

    public function getQrCount()
    {
        // Customer has_many Order via Order.customer_id -> id
        return $this->hasMany(Qr::className(), ['route_ID' => 'route_ID'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'create_user_ID'])->one();
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
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID'])->one();
    }

    public function setRouteOrder()
    {var_dump($this->day_date);exit;
        $max_order = Route::find()
            ->select('route_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('day_date=:day_date')
            ->addParams(
                [
                    ':event_ID' => $this->event_ID,
                    ':day_date' => $this->day_date,
                ])
            ->max('route_volgorde');
        $this->route_volgorde = $max_order++;
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