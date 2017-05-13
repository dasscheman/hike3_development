<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\Components\SetupDateTime;

/**
 * This is the model class for table "tbl_posten".
 *
 * @property integer $post_ID
 * @property string $post_name
 * @property integer $event_ID
 * @property string $date
 * @property integer $post_volgorde
 * @property integer $score
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property PostPassage[] $PostPassages
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 */
class Posten extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_posten';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_name', 'event_ID', 'score'], 'required'],
            [['event_ID', 'post_volgorde', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['date', 'create_time', 'update_time'], 'safe'],
            [['post_name'], 'string', 'max' => 255],
            [
                ['post_name', 'event_ID', 'date'],
                'unique',
                'targetAttribute' => ['post_name', 'event_ID', 'date'],
                'message' => Yii::t('app', 'This station name exist for this day.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_ID' => Yii::t('app', 'Station ID'),
            'post_name' => Yii::t('app', 'Station Name'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'date' => Yii::t('app', 'Date'),
            'post_volgorde' => Yii::t('app', 'Station Order'),
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
            $this->event_ID = Yii::$app->user->identity->selected;
            return(true);
        }
        return(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['post_ID' => 'post_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['post_ID' => 'post_ID']);
    }

    public function getPostPassagesCount()
    {
        return $this->hasMany(PostPassage::className(), ['post_ID' => 'post_ID'])->count();
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

	/**
    * Retrieves a list of post namen
    * @return array an array of all available posten'.
    */
    public function getPostNameOptions($event_Id)
    {
    	$data = Posten::findAll('event_ID =:event_Id', array(':event_Id' => $event_Id));
        $list = CHtml::listData($data, 'post_ID', 'post_name');
        return $list;
    }

    public function getPostNameOptionsToday($date)
    {
        $event_id = Yii::$app->user->identity->selected;
		// $active_day = EventNames::getActiveDayOfHike($event_id);
        $date = Yii::$app->setupdatetime->convert($date);
    	$data = Posten::find()
            ->where('event_ID =:event_id AND date =:date')
            ->addParams([
                ':event_id' => $event_id,
			    ':date' => $date
            ])
            ->asArray()
           ->all();
        $listData = ArrayHelper::map($data,'post_ID','post_name');
        return $listData;
    }

    /**
    * Retrieves the score of an post.
    */
    public function getPostScore($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->score) ?
            $data->score : 0;
    }

    /**
    * Haald de post naam op aan de hand van een post ID.
    */
    public function getPostName($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->post_name) ?
            $data->post_name : "nvt";
    }

    public function getDatePost($post_Id)
    {
    	$data = Posten::find('post_ID =:post_Id', array(':post_Id' => $post_Id));
        return isset($data->date) ?
            $data->date : "nvt";
    }

	public function setNewOrderForPosten()
	{
        $max_order = Posten::find()
            ->select('post_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('date=:date')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':date' =>$this->date,
                ])
            ->max('post_volgorde');
        if (empty($max_order)) {
            // dd(empty($max_order));
            $this->post_volgorde = 1;
        } else {
            $this->post_volgorde = $max_order+1;
        }
	}

	public function lowererOrderNumberExists($post_id)
	{
        $data = Posten::findOne($post_id);
        $dataNext = Posten::find()
            ->where('event_ID =:event_id AND date =:date AND post_volgorde <:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->date, ':order' => $data->post_volgorde])
            ->orderBy('post_volgorde DESC')
            ->exists();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

	public function higherOrderNumberExists($post_id)
	{
        $data = Posten::findOne($post_id);
        $dataNext = Posten::find()
            ->where('event_ID =:event_id AND date =:date AND post_volgorde >:order')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':date' => $data->date, ':order' => $data->post_volgorde])
            ->orderBy('post_volgorde ASC')
            ->exists();

		if ($dataNext) {
			return TRUE;
        }
        return FALSE;
	}

    /**
    * Return the first station of the date
    */
	public function getStartPost($date)
	{
        $event_id = Yii::$app->user->identity->selected;
        $data = Posten::find()
            ->where('event_ID =:event_id AND date =:date')
            ->params([':event_id' => $event_id, ':date' => $date])
            ->orderBy(['post_volgorde'=>SORT_ASC])
            ->one();

		if (isset($data->post_ID))
		{
			return $data->post_ID;
		} else {
			return false;
		}
	}

    /**
    * Checks if the post is a start post of a day.
    */
	public function isStartPost($post_id)
	{
		if (Posten::higherOrderNumberExists($post_id)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function existPostForActiveDay($event_id)
	{
        dd('NIET MEER NODIG??');
		$date = EventNames::getActiveDayOfHike($event_id);

		$criteria = new CDbCriteria();
		$criteria->condition = 'event_ID =:event_id AND date =:date';
		$criteria->params=array(':event_id' => $event_id, ':date' =>$date);
		$criteria->order = "post_volgorde ASC";
		$data = Posten::find($criteria);

		if (isset($data->post_ID))
		{
			return true;
		} else {
			return false;
		}
	}

	public function startPostExist($date)
	{
        $exists = Posten::find()
            ->where('event_ID=:event_id')
            ->andwhere('date=:date')
            ->addParams(
                [
                    ':event_id' => Yii::$app->user->identity->selected,
                    ':date' => $date,
                ])
            ->exists();
        return $exists;
	}
}
