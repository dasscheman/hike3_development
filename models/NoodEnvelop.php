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
class TblNoodEnvelop extends HikeActiveRecord
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
}
<?php
// Created: 2014
// Modified: 23 feb 2015

/**
 * This is the model class for table "tbl_nood_envelop".
 *
 * The followings are the available columns in table 'tbl_nood_envelop':
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
 * The followings are the available model relations:
 * @property Users $createUser
 * @property EventNames $event
 * @property Route $route
 * @property Users $updateUser
 * @property OpenNoodEnvelop[] $openNoodEnvelops
 */
class NoodEnvelop extends HikeActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return NoodEnvelop the static model class
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
		return 'tbl_nood_envelop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nood_envelop_name, event_ID, route_ID, coordinaat, opmerkingen, score', 'required'),
			array('event_ID, route_ID, nood_envelop_volgorde, score, create_user_ID, update_user_ID', 'numerical', 'integerOnly'=>true),
			array('nood_envelop_name, coordinaat', 'length', 'max'=>255),
			array('opmerkingen', 'length', 'max'=>1050),
			array('create_time, update_time', 'safe'),
			array('nood_envelop_name', 'ext.UniqueAttributesValidator', 'with'=>'event_ID,route_ID'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('nood_envelop_ID, nood_envelop_name, event_ID, route_ID, nood_envelop_volgorde, coordinaat, opmerkingen, score, create_time, create_user_ID, update_time, update_user_ID', 'safe', 'on'=>'search'),
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
			'createUser' => array(self::BELONGS_TO, 'Users', 'create_user_ID'),
			'event' => array(self::BELONGS_TO, 'EventNames', 'event_ID'),
			'route' => array(self::BELONGS_TO, 'Route', 'route_ID'),
			'updateUser' => array(self::BELONGS_TO, 'Users', 'update_user_ID'),
			'openNoodEnvelops' => array(self::HAS_MANY, 'OpenNoodEnvelop', 'nood_envelop_ID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nood_envelop_ID' => 'Nood Envelop',
			'nood_envelop_name' => 'Hint Naam',
			'event_ID' => 'Event',
			'route_ID' => 'Route',
			'nood_envelop_volgorde' => 'Hint Volgorde',
			'coordinaat' => 'Coordinaat',
			'opmerkingen' => 'Opmerkingen',
			'score' => 'Strafpunten',
			'create_time' => 'Create Time',
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

		$criteria=new CDbCriteria;

		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
		$criteria->compare('nood_envelop_name',$this->nood_envelop_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('nood_envelop_volgorde',$this->nood_envelop_volgorde);
		$criteria->compare('coordinaat',$this->coordinaat,true);
		$criteria->compare('opmerkingen',$this->opmerkingen,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchHints($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
		$criteria->compare('nood_envelop_name',$this->nood_envelop_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->condition = 'event_ID=:event_id';
		$criteria->params=array(':event_id'=>$event_id);
		$criteria->order= 'route_ID ASC, nood_envelop_volgorde ASC';
		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('nood_envelop_volgorde',$this->nood_envelop_volgorde);
		$criteria->compare('coordinaat',$this->coordinaat,true);
		$criteria->compare('opmerkingen',$this->opmerkingen,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);

		$hikeStatus = EventNames::model()->getStatusHike($event_id);
		$rolPlayer = DeelnemersEvent::model()->getRolOfPlayer($event_id, Yii::app()->user->id);
		$route_id = NoodEnvelop::model()->getRouteIdOfEnvelop($model_id);

		if ($action_id == 'moveUpDown'){
			if (!isset($order) || !isset($route_id)){
				return $actionAllowed;
			}
			if ($hikeStatus != EventNames::STATUS_opstart or
				$rolPlayer != DeelnemersEvent::ROL_organisatie) {
					return $actionAllowed;
			}

			if ($move == 'up'){
				$nextOrderExist = NoodEnvelop::model()->higherOrderNumberExists($event_id,
																				$model_id,
																				$order,
																				$route_id);
			}
			if ($move == 'down'){
				$nextOrderExist = NoodEnvelop::model()->lowererOrderNumberExists($event_id,
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