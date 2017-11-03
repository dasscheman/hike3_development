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
            [['open_vragen_name', 'event_ID', 'route_ID', 'vraag', 'goede_antwoord', 'score'], 'required'],
            [['event_ID', 'route_ID', 'vraag_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['omschrijving'], 'string'],
            [['create_time', 'update_time'], 'safe'],
            [['open_vragen_name', 'vraag', 'goede_antwoord'], 'string', 'max' => 255],
            [
                ['open_vragen_name', 'event_ID', 'route_ID'],
                'unique',
                'targetAttribute' => ['open_vragen_name', 'event_ID', 'route_ID'],
                'message' => Yii::t('app', 'Question name already exists for this route section')]
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
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['open_vragen_ID' => 'open_vragen_ID']);
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
     * Score ophalen voor een group.
     */
    public function isQuestionAwnseredByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = OpenVragenAntwoorden::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND open_vragen_ID =:vraag_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':vraag_id' => $this->open_vragen_ID])
            ->exists();

        return $data;
    }

    /**
     * Score ophalen voor een group.
     */
    public function getAwnserByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = OpenVragenAntwoorden::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND open_vragen_ID =:vraag_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':vraag_id' => $this->open_vragen_ID])
            ->one();

        return $data;
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

	public function setNewOrderForVragen()
	{
        $max_order = OpenVragen::find()
            ->select('vraag_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('route_ID=:route_id')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':route_id' =>$this->route_ID,
                ])
            ->max('vraag_volgorde');
        if (empty($max_order)) {
            $this->vraag_volgorde = 1;
        } else {
            $this->vraag_volgorde = $max_order+1;
        }
	}
}
