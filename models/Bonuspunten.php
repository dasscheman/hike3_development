<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_bonuspunten".
 *
 * @property integer $bouspunten_ID
 * @property integer $event_ID
 * @property string $date
 * @property integer $post_ID
 * @property integer $group_ID
 * @property string $omschrijving
 * @property integer $score
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Posten $post
 * @property Users $updateUser
 */
class Bonuspunten extends HikeActiveRecord
{
	public $group_name;
	public $post_name;
	public $route_name;
	public $username;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_bonuspunten';
    }

    /**
     * @inheritdoc
     */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			[['event_ID', 'group_ID', 'omschrijving',], 'required'],
			[['event_ID', 'post_ID', 'group_ID', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
			[['event_ID', 'post_ID', 'group_ID', 'score','create_time', 'update_time', 'date', 'create_time', 'update_time'], 'safe'],
			[['omschrijving'], 'string', 'max' => 255],
			[
			    ['omschrijving', 'group_ID', 'event_ID'],
                'unique',
                'targetAttribute' => ['omschrijving', 'group_ID', 'event_ID'],
                'message' => Yii::t('app', 'These points are already assigned to this group.')
			]
		];
	}


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bouspunten_ID' => Yii::t('app', 'Bonus points ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'date' => Yii::t('app', 'Date'),
            'post_ID' => Yii::t('app', 'Post ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'omschrijving' => Yii::t('app', 'Description'),
            'score' => Yii::t('app', 'Score'),
            'create_time' => Yii::t('app', 'Given at'),
            'create_user_ID' => Yii::t('app', 'Given by'),
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
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['group_ID' => 'group_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posten::className(), ['post_ID' => 'post_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }

	/**
	 * Returns de totale score die een groep heeft gehaald met bnuspunten.
	 */
	public function getBonuspuntenScore($group_id)
	{
        $data = Bonuspunten::find()
            ->select('SUM(score) as score')
            ->where('event_ID =:event_id AND group_ID =:group_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':group_id' => $group_id])
            ->one();

	    if(isset($data->score))
        {
            return (int) $data->score;
        } else {
            return 0;
        }
	}
}
