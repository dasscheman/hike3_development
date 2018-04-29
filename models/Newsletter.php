<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "newsletter".
 *
 * @property int $id
 * @property string $subject
 * @property string $body
 * @property int $is_active
 * @property string $schedule_date_time
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property NewsletterCampaignsQueue[] $newsletterCampaignsQueues
 */
class Newsletter extends HikeActiveRecord
{
    const STATUS_actief = 1;
    const STATUS_niet_actief = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            [['schedule_date_time', 'create_time', 'update_time'], 'safe'],
            [['create_user_ID', 'update_user_ID'], 'integer'],
            [['subject'], 'string', 'max' => 45],
            [['body'], 'string', 'max' => 1050],
            [['is_active'], 'string', 'max' => 1],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject' => 'Subject',
            'body' => 'Body',
            'is_active' => 'Active',
            'schedule_date_time' => 'Schedule Date Time',
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletterMailLists()
    {
        return $this->hasMany(NewsletterMailList::className(), ['newsletter_id' => 'id']);
    }
}
