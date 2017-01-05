<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_qr".
 *
 * @property integer $qr_ID
 * @property string $qr_name
 * @property string $qr_code
 * @property integer $event_ID
 * @property integer $route_ID
 * @property integer $qr_volgorde
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
 * @property QrCheck[] $QrChecks
 */
class Qr extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_qr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qr_name', 'qr_code', 'event_ID', 'route_ID', 'score'], 'required'],
            [['event_ID', 'route_ID', 'qr_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['qr_name', 'qr_code'], 'string', 'max' => 255],
            [
                ['qr_code', 'event_ID'],
                'unique', 
                'targetAttribute' => ['qr_code', 'event_ID'], 
                'message' => Yii::t('app/error', 'This qr code exists for this hike')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'qr_ID' => Yii::t('app', 'Qr ID'),
            'qr_name' => Yii::t('app', 'Qr Name'),
            'qr_code' => Yii::t('app', 'Qr Code'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'route_ID' => Yii::t('app', 'Route ID'),
            'qr_volgorde' => Yii::t('app', 'Qr Order'),
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
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['qr_ID' => 'qr_ID']);
    }

	public function getUniqueQrCode()
	{

        dd('NIET MEER NODIG??');
		$UniqueQrCode = 99;
		$event_id = $_GET['event_id'];
		while($UniqueQrCode == 99)
		{
			$newqrcode = GeneralFunctions::randomString(22);

			$data = Qr::find('event_ID = :event_Id AND qr_code=:qr_code',
						    array(':event_Id' => $event_id,
							  ':qr_code' => $newqrcode));
			// if QR code niet bestaat dan wordt de nieuwe gegenereede code gebruikt
			if(!isset($data))
			{
				$UniqueQrCode = $newqrcode;
			}
		}
		return($UniqueQrCode);
	}

	public function getQrCode($event_id, $qr_id)
	{

        dd('NIET MEER NODIG??');
		$data = Qr::find('event_ID = :event_Id AND qr_ID=:qr_id',
						    array(':event_Id' => $event_id,
							  ':qr_id' => $qr_id));
		return($data->qr_code);
	}

	public function getQrRouteID($qr_id)
	{

        dd('NIET MEER NODIG??');
		$data = Qr::find('qr_ID=:qr_id',
						    array(':qr_id' => $qr_id));
		if(isset($data->route_ID)){
			return $data->route_ID;
		} else {
			return false;
		}
	}

	public function getQrId($event_id, $qr_code)
	{

        dd('NIET MEER NODIG??');
		$data = Qr::find('event_ID = :event_Id AND qr_code=:qr_code',
						    array(':event_Id' => $event_id,
							  ':qr_code' => $qr_code));
		return($data->qr_ID);
	}


	public function getQrCodeName($event_id, $qr_id)
	{

        dd('NIET MEER NODIG??');
		$data = Qr::find('event_ID = :event_Id AND qr_ID=:qr_id',
						    array(':event_Id' => $event_id,
							  ':qr_id' => $qr_id));
		return($data->qr_name);
	}

	public function getNewOrderForIntroductieQr($event_id)
	{

        dd('NIET MEER NODIG??');

        $route_id = Route::getIntroductieRouteId($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);
		$criteria->order = "qr_volgorde DESC";
		$criteria->limit = 1;

		if (Qr::model()->exists($criteria))
		{	$data = Qr::findAll($criteria);
			$newOrder = $data[0]->qr_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}

	public function getNewOrderForQr($event_id, $route_id)
	{

        dd('NIET MEER NODIG??');
		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND route_ID =:route_id';
		$criteria->params=array(':event_id' => $event_id, ':route_id' =>$route_id);
		$criteria->order = "qr_volgorde DESC";
		$criteria->limit = 1;

		if (Qr::model()->exists($criteria))
		{	$data = Qr::findAll($criteria);
			$newOrder = $data[0]->qr_volgorde+1;
		} else {
			$newOrder = 1;}

		return $newOrder;
	}

	public function getNumberQrRouteId($route_id)
	{
        dd('NIET MEER NODIG??');
        $data = Qr::find()
            ->where('event_ID =:event_id AND route_ID =:route_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':route_id' =>$route_id])
            ->count();

		return $data;
	}

	public function lowererOrderNumberExists($qr_id)
	{
        $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('event_ID =:event_id AND qr_ID !=:id AND route_ID=:route_id AND qr_volgorde >=:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':id' => $data->qr_ID, ':route_id' => $$data->route_ID, ':order' => $data->qr_order])
            ->exist();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function higherOrderNumberExists($event_id, $id, $qr_order, $route_id)
	{
        $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('event_ID =:event_id AND qr_ID !=:id AND route_ID=:route_id AND qr_volgorde >=:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':id' => $data->qr_ID, ':route_id' => $$data->route_ID, ':order' => $data->qr_order])
            ->exist();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function qrExistForRouteId($event_id, $route_id)
	{
        $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('route_ID =:event_id AND route_ID =:route_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':route_id' => $data->route_ID])
            ->exist();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

    /**
    * Retrieves the score of an post.
    */
    public function getQrScore($qr_Id)
    {
    	$data = Qr::find('qr_ID =:qr_Id', array(':qr_Id' => $qr_Id));
        return isset($data->score) ?
            $data->score : 0;
    }

}