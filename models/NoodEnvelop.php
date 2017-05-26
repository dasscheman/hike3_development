<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_nood_envelop".
 *
 * @property integer $nood_envelop_ID
 * @property string $nood_envelop_name
 * @property integer $event_ID
 * @property integer $route_ID
 * @property integer $nood_envelop_volgorde
 * @property string $coordinaat
 * @property string $opmerkingen
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
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 */
class NoodEnvelop extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_nood_envelop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nood_envelop_name', 'event_ID', 'route_ID', 'opmerkingen', 'score'], 'required'],
            [['event_ID', 'route_ID', 'nood_envelop_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['nood_envelop_name', 'coordinaat'], 'string', 'max' => 255],
            [['opmerkingen'], 'string', 'max' => 1050],
            [
                ['nood_envelop_name', 'event_ID'],
                'unique', 'targetAttribute' => ['nood_envelop_name', 'event_ID'],
                'message' => Yii::t('app', 'This hint name alrady exists for this Hike')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nood_envelop_ID' => Yii::t('app', 'Hint ID'),
            'nood_envelop_name' => Yii::t('app', 'Hint Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'route_ID' => Yii::t('app', 'Route ID'),
            'nood_envelop_volgorde' => Yii::t('app', 'Hint Order'),
            'coordinaat' => Yii::t('app', 'Coordinate'),
            'opmerkingen' => Yii::t('app', 'Remarks'),
            'score' => Yii::t('app', 'Penalty points'),
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
            $this->event_ID = Yii::$app->user->identity->selected;
            return(true);
        }
        return(false);
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
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID']);
    }

	public function getNoodEnvelopName($envelop_id)
	{

        dd('DEZE IS depricated');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->nood_envelop_name))
			{return($data->nood_envelop_name);}
		else
			{return;}
	}

    /**
    * Retrieves the score of an post.
    */
    public function getNoodEnvelopScore($envelop_id)
    {
    	$data = NoodEnvelop::model()->find('nood_envelop_ID =:envelop_id', array(':envelop_id' => $envelop_id));
        return isset($data->score) ?
            $data->score : 0;
    }

	public function getCoordinaten($envelop_id)
	{
        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->coordinaat))
			{return($data->coordinaat);}
		else
			{return;}
	}

	public function getOpmerkingen($envelop_id)
	{
        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->opmerkingen))
			{return($data->opmerkingen);}
		else
			{return;}
	}

	public function getEventDayOfEnvelop($envelop_id)
	{
        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->route_ID))
		{
			$date = Route::model()->getDayOfRouteId($data->route_ID);
			return $date;
		}
		else
			{return;}
	}

	public function getRouteIdOfEnvelop($envelop_id)
	{
		$data = NoodEnvelop::model()->find('nood_envelop_ID =:envelop_id',
						  array(':envelop_id' => $envelop_id));
		if(isset($data->route_ID)){
			return $data->route_ID;
		} else {
			return false;
		}
	}

	public function getNoodEnvelopVolgnummer($envelop_id)
	{
        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->nood_envelop_volgorde))
			{return($data->nood_envelop_volgorde);}
		else
			{return('Geen Hint volgnummer beschikbaar.');}
	}

	public function getNumberNoodEnvelopRouteId($event_id, $route_id)
	{
        dd('NIET MEER NODIG??');
        $criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);

		return NoodEnvelop::model()->count($criteria);
	}

	public function getRouteNameOfEnvelopId($envelop_id)
	{
        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->route_ID))
			{return(Route::model()->getRouteName($data->route_ID));}
		else
			{return('Geen Hint volgnummer beschikbaar.');}
	}

	public function setNewOrderForNoodEnvelop()
	{
        $max_order = NoodEnvelop::find()
            ->select('nood_envelop_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('route_ID=:route_id')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':route_id' =>$this->route_ID,
                ])
            ->max('nood_envelop_volgorde');
        if (empty($max_order)) {
            $this->nood_envelop_volgorde = 1;
        } else {
            $this->nood_envelop_volgorde = $max_order+1;
        }
	}

	public function lowererOrderNumberExists($event_id, $id, $envelop_order, $route_id)
	{
        dd('NIET MEER NODIG??');
                $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('event_ID =:event_id AND qr_ID !=:id AND route_ID=:route_id AND qr_volgorde >=:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->day_date, ':order' => $data->route_order])
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->day_date, ':order' => $data->route_order])
            ->exist();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;





		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND nood_envelop_ID !=:id AND route_ID=:route_id AND nood_envelop_volgorde >=:order';
		$criteria->params=array(':event_id' => $event_id,
								':id' => $id,
								':route_id' => $route_id ,
								':order' => intval($envelop_order));

		if (NoodEnvelop::model()->exists($criteria))
			return true;
		else
			return false;
	}

	public function higherOrderNumberExists($event_id, $id, $envelop_order, $route_id)
	{
        dd('NIET MEER NODIG??');
                $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('event_ID =:event_id AND qr_ID !=:id AND route_ID=:route_id AND qr_volgorde >=:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->day_date, ':order' => $data->route_order])
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->day_date, ':order' => $data->route_order])
            ->exist();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;








		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND nood_envelop_ID !=:id AND route_ID =:route_id AND nood_envelop_volgorde <=:order';
		$criteria->params=array(':event_id' => $event_id,
								':id' => $id,
								':route_id' => $route_id,
								':order' => intval($envelop_order));

		if (NoodEnvelop::model()->exists($criteria))
			return true;
		else
			return false;
	}

    /**
     * Score ophalen voor een group.
     */
    public function isHintOpenedByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND nood_envelop_ID =:nood_envelop_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':group_id' => $group_id->group_ID, ':nood_envelop_id' => $this->nood_envelop_ID])
            ->exists();

        return $data;
    }

    /**
     * Score ophalen voor een group.
     */
    public function getHintOpenedByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND nood_envelop_ID =:nood_envelop_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':group_id' => $group_id->group_ID, ':nood_envelop_id' => $this->nood_envelop_ID])
            ->one();

        if($data === NULL) {
            $data = new OpenNoodEnvelop;
        }

        return $data;
    }

}
