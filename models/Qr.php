<?php

namespace app\models;

use Yii;
use app\components\GeneralFunctions;

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
                'message' => Yii::t('app', 'This qr code exists for this hike')]
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
		$UniqueQrCode = 99;

		while($UniqueQrCode == 99)
		{
			$newqrcode = GeneralFunctions::randomString(22);
			$data = Qr::find()
                ->where('qr_code = :qr_code')
			    ->params([':qr_code' => $newqrcode])
                ->exists();
			// if QR code niet bestaat dan wordt de nieuwe gegenereede code gebruikt
			if(!$data)
			{
				$UniqueQrCode = $newqrcode;
			}
		}
		return($UniqueQrCode);
	}

	public function setNewOrderForQr()
	{
        $max_order = Qr::find()
            ->select('qr_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('route_ID=:route_id')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':route_id' =>$this->route_ID,
                ])
            ->max('qr_volgorde');

        if (empty($max_order)) {
            $this->qr_volgorde = 1;
        } else {
            $this->qr_volgorde = $max_order+1;
        }
	}

	public function lowererOrderNumberExists($qr_id)
	{
        $data = Qr::find($qr_id);
        $dataNext = Qr::find()
            ->where('event_ID =:event_id AND qr_ID !=:id AND route_ID=:route_id AND qr_volgorde >=:order')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':id' => $data->qr_ID, ':route_id' => $$data->route_ID, ':order' => $data->qr_order])
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
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':id' => $data->qr_ID, ':route_id' => $$data->route_ID, ':order' => $data->qr_order])
            ->exists();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function qrExistForRouteId($route_id)
	{
        $data = Qr::find()
            ->where('route_ID =:event_id AND route_ID =:route_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':route_id' => $route_id])
            ->exists();

		if ($data) {
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


    /**
     * Score ophalen voor een group.
     */
    public function isSilentStationCkeckedByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = QrCheck::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND qr_ID =:qr_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':qr_id' => $this->qr_ID])
            ->exists();

        return $data;
    }

    /**
     * Score ophalen voor een group.
     */
    public function getSilentStationCkeckedByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = QrCheck::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND qr_ID =:qr_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':qr_id' => $this->qr_ID])
            ->one();

        return $data;
    }



}
