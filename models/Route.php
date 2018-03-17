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
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['route_ID' => 'route_ID']);
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
        // EXAMPLE
        return $this->hasMany(OpenVragen::className(), ['route_ID' => 'route_ID']);
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
        return $this->hasMany(Qr::className(), ['route_ID' => 'route_ID']);
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

    public function setRouteOrder()
    {
        $max_order = Route::find()
            ->select('route_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('day_date=:day_date')
            ->addParams(
                [
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':day_date' => $this->day_date,
                ])
            ->max('route_volgorde');
        if (empty($max_order)) {
            $this->route_volgorde = 1;
        } else {
            $this->route_volgorde = $max_order+1;
        }
    }

	public function getDayOfRouteId($id)
	{
		$data = Route::find()
            ->where('route_ID =:route_id')
            ->params([':route_id' => $id])
            ->one();
        if ($data)
		{
            return $data->day_date;
		}
		return FALSE;
	}

	public function getRouteName($id)
	{
		$data = Route::find()
            ->where('route_ID =:id')
            ->params([':id' => $id])
            ->one();

		if ($data)
		{
            return "nvt";
		} else {
			return $data->route_name;
        }
	}

	public function getIntroductieRouteId()
	{
        $data = Route::find()
            ->where('event_ID =:event_id AND route_name =:route_name')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_name' =>'Introductie'])
            ->order('route_volgorde DESC')
            ->one();

		if ($data) {
			$introductie_id = 1;
        } else {
			$data = Route::findAll($criteria);
			$introductie_id = $data->route_ID;
		}

		return $introductie_id;
	}

	public function routeIdIntroduction($route_id)
	{
		$data = Route::find($route_id);
		if ($data->route_name == "Introductie")
		{
			return true;
		}
		return false;
	}

	public function lowererOrderNumberExists($route_id)
	{
        $data = Route::findOne($route_id);
        $dataNext = Route::find()
            ->where('event_ID =:event_id AND day_date =:date AND route_volgorde <:order')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $data->day_date, ':order' => $data->route_volgorde])
            ->orderBy('route_volgorde DESC')
            ->exists();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function higherOrderNumberExists($route_id)
	{
        $data = Route::findOne($route_id);

        $dataNext = Route::find()
            ->where('event_ID =:event_id AND day_date =:date AND route_volgorde >:order')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':date' => $data->day_date, ':order' => $data->route_volgorde])
            ->orderBy('route_volgorde ASC')
            ->exists();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function routeExistForDay($date)
	{
        $exists = Route::find()
            ->where('event_ID=:event_id')
            ->andwhere('day_date=:day_date')
            ->addParams(
                [
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':day_date' => $date,
                ])
            ->exists();
        return $exists;
	}
}
