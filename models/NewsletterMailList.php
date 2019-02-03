<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_newsletter_mail_list".
 *
 * @property string $id
 * @property int $newsletter_id
 * @property int $user_id
 * @property string $email
 * @property string $send_time
 * @property int $is_sent
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property TblUsers $createUser
 * @property TblNewsletter $newsletter
 * @property TblUsers $user
 * @property TblUsers $updateUser
 */
class NewsletterMailList extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_newsletter_mail_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsletter_id', 'user_id', 'email'], 'required'],
            [['newsletter_id', 'user_id', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['send_time', 'create_time', 'update_time'], 'safe'],
            [['email'], 'string', 'max' => 150],
            [['is_sent'], 'string', 'max' => 1],
            [['create_user_ID'], 'exist', 'skipOnError' => false, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['newsletter_id'], 'exist', 'skipOnError' => false, 'targetClass' => Newsletter::className(), 'targetAttribute' => ['newsletter_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => false, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['update_user_ID'], 'exist', 'skipOnError' => false, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'newsletter_id' => 'Newsletter ID',
            'user_id' => 'User ID',
            'email' => 'Email',
            'send_time' => 'Send Time',
            'is_sent' => 'Is Sent',
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
        return $this->hasOne(Users::className(), ['id' => 'create_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsletter()
    {
        return $this->hasOne(Newsletter::className(), ['id' => 'newsletter_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }


    public function sendNewswletters()
    {
        $user_mails = NewsletterMailList::find()
            ->where('is_sent != true')
            ->andWhere('ISNULL(send_time)')
            ->all();

        $count = 0;
        foreach ($user_mails as $user_mail) {
            if ($count > 50) {
                // Om te voorkomen dat er teveel mail ineens wordt gestuurd en dat
                // gezien wordt als spam.
                break;
            }
            $mail = $user_mail->getNewsletter()->one();
            if ($mail->is_active == false) {
                continue;
            }
            $user = $user_mail->getUser()->one();
            if (isset($user->tussenvoegsel)) {
                $naam = $user->voornaam . ' ' . $user->tussenvoegsel . ' ' . $user->achternaam;
            } else {
                $naam = $user->voornaam . ' ' . $user->achternaam;
            }
            $body = str_replace("{{username}}", $naam, $mail->body);

            Yii::$app->mailer->compose('newsletter', [
                    'body' => $body,
                    'user_id' => $user_mail->user_id,
                    'email' => $user_mail->email
                ])
                ->setFrom(Yii::$app->params["admin_email"])
                ->setTo($user_mail->email)
                ->setSubject($mail->subject)
                ->send();

            $user_mail->is_sent = '1';
            $user_mail->send_time = Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
            $user_mail->save();
            $count++;
        }
        return $count;
    }
}
