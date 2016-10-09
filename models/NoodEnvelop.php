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
            [['nood_envelop_name', 'event_ID', 'route_ID', 'coordinaat', 'opmerkingen', 'score'], 'required'],
            [['event_ID', 'route_ID', 'nood_envelop_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['nood_envelop_name', 'coordinaat'], 'string', 'max' => 255],
            [['opmerkingen'], 'string', 'max' => 1050],
            [   
                ['nood_envelop_name', 'event_ID'], 
                'unique', 'targetAttribute' => ['nood_envelop_name', 'event_ID'],   
                'message' => Yii::t('app/error', 'This hint name alrady exists for this Hike')]
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
    public function getRoute()
    {
        return $this->hasOne(TblRoute::className(), ['route_ID' => 'route_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(TblUsers::className(), ['user_ID' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblOpenNoodEnvelops()
    {
        return $this->hasMany(TblOpenNoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID']);
    }

	public function getNoodEnvelopName($envelop_id)
	{
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
        $criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);

		return NoodEnvelop::model()->count($criteria);
	}

	public function getRouteNameOfEnvelopId($envelop_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition="nood_envelop_ID = $envelop_id";
		$data = NoodEnvelop::model()->find($criteria);
		if(isset($data->route_ID))
			{return(Route::model()->getRouteName($data->route_ID));}
		else
			{return('Geen Hint volgnummer beschikbaar.');}
	}

	public function getNewOrderForNoodEnvelop($event_id, $route_id)
	{
        $criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);
		$criteria->order = "nood_envelop_volgorde DESC";
		$criteria->limit = 1;

		if (NoodEnvelop::model()->exists($criteria))
		{
			$data = NoodEnvelop::model()->findAll($criteria);
			$newOrder = $data[0]->nood_envelop_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}

	public function lowererOrderNumberExists($event_id, $id, $envelop_order, $route_id)
	{
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
}