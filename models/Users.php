<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii\base\Model;

/**
 * This is the model class for table "tbl_users".
 *
 * @property integer $user_ID
 * @property string $username
 * @property string $voornaam
 * @property string $achternaam
 * @property string $organisatie
 * @property string $email
 * @property string $password
 * @property string $macadres
 * @property string $birthdate
 * @property string $last_login_time
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
* @property integer $selected_event_ID 
* @property string $authKey 
* @property string $accessToken 
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property Bonuspunten[] $Bonuspuntens0
 * @property DeelnemersEvent[] $DeelnemersEvents
 * @property DeelnemersEvent[] $DeelnemersEvents0
 * @property DeelnemersEvent[] $DeelnemersEvents1
 * @property EventNames[] $EventNames
 * @property EventNames[] $EventNames0
 * @property FriendList[] $FriendLists
 * @property FriendList[] $FriendLists0
 * @property FriendList[] $FriendLists1
 * @property FriendList[] $FriendLists2
 * @property Groups[] $Groups
 * @property Groups[] $Groups0
 * @property NoodEnvelop[] $NoodEnvelops
 * @property NoodEnvelop[] $NoodEnvelops0
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops0
 * @property OpenVragen[] $OpenVragens
 * @property OpenVragen[] $OpenVragens0
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens0
 * @property PostPassage[] $PostPassages
 * @property PostPassage[] $PostPassages0
 * @property Posten[] $Postens
 * @property Posten[] $Postens0
 * @property Qr[] $Qrs
 * @property Qr[] $Qrs0
 * @property QrCheck[] $QrChecks
 * @property QrCheck[] $QrChecks0
 * @property Route[] $Routes
 * @property Route[] $Routes0
 */
class Users extends HikeActiveRecord implements IdentityInterface
{    
    public $selected_event_ID = 123;
    public $password_repeat;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'voornaam', 'achternaam', 'email', 'password', 'password_repeat'], 'required'],
            [['birthdate', 'last_login_time', 'create_time', 'update_time'], 'safe'],
            [['create_user_ID', 'update_user_ID', 'selected_event_ID'], 'integer'],
            [['username', 'voornaam', 'achternaam', 'organisatie', 'email', 
                'password', 'macadres', 'authKey', 'accessToken'], 'string', 'max' => 255],
            ['username', 'unique'],
            ['email', 'unique'],
            [['email'], 'email'],
            [
                'birthdate', 
                'date', 
                'message' => Yii::t('app', '{attribute}: This is not a date!'), 
                'format' => 'yyyy-MM-dd'
            ],
            ['password', 'compare', 'on'=>'ChangePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_ID' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'voornaam' => Yii::t('app', 'First Name'),
            'achternaam' => Yii::t('app', 'Surname'),
            'organisatie' => Yii::t('app', 'Organisation'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'macadres' => Yii::t('app', 'Macadres'),
            'birthdate' => Yii::t('app', 'Birthdate'),
            'last_login_time' => Yii::t('app', 'Last Login Time'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
            'selected_event_ID' => Yii::t('app', 'Selected Event ID'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
        ];
    }

    /**
     * Only the actions specific to the model User are here defined.
     */
    function isActionAllowed($controller_id = null, $action_id = null, $model_id = null, $group_id = null)
    {
		$actionAllowed = parent::isActionAllowed($controller_id, $action_id, $model_id, $group_id);

        if ($controller_id == 'users'){
            if (in_array($action_id, ['decline', 'accept'])) {
                return true;
            }
        }
        return false;
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->authKey = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens0()
    {
        return $this->hasMany(Bonuspunten::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents0()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents1()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames()
    {
        return $this->hasMany(EventNames::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames0()
    {
        return $this->hasMany(EventNames::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists()
    {
        return $this->hasMany(FriendList::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists0()
    {
        return $this->hasMany(FriendList::className(), ['friends_with_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists1()
    {
        return $this->hasMany(FriendList::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists2()
    {
        return $this->hasMany(FriendList::className(), ['user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups0()
    {
        return $this->hasMany(Groups::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops0()
    {
        return $this->hasMany(NoodEnvelop::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops0()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens()
    {
        return $this->hasMany(OpenVragen::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens0()
    {
        return $this->hasMany(OpenVragen::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens0()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages0()
    {
        return $this->hasMany(PostPassage::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens()
    {
        return $this->hasMany(Posten::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens0()
    {
        return $this->hasMany(Posten::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs()
    {
        return $this->hasMany(Qr::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs0()
    {
        return $this->hasMany(Qr::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks0()
    {
        return $this->hasMany(QrCheck::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes0()
    {
        return $this->hasMany(Route::className(), ['update_user_ID' => 'user_ID']);
    }
    
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->user_ID;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
/* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
          return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function hashPassword($password)
    {
        return md5($password);
    }
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $this->hashPassword($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * 
     */
    public function getSelectedEventID()
    {
        $this->selected_event_ID = 22 /*(new \yii\db\Query())
            ->from('tbl_users')
            ->where(['user_ID' => Yii::$app->user->id])
            ->one()*/;
        return $this->selected_event_ID;
    } 
    
    /**
    * Retrieves a list of users
    * @return array an array of all available users'.
    */
    public function getUserNameOptions()
    {
        $data	= Users::model()->findAll();
        $list = CHtml::listData($data, 'user_ID', 'username');
        return $list;
    }

    /**
    * Retrieves username
    */
    public function getUserName($user_Id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_ID =:id';
        $criteria->params=array(':id' => $user_Id);

        if (Users::model()->exists($criteria))
        {
        $data = Users::model()->find($criteria);
            return $data->username;
        } else {
            return;
        }
    }

    /**
    * Retrieves username
    */
    public function getUserEmail($user_Id)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_ID =:id';
        $criteria->params=array(':id' => $user_Id);

        if (Users::model()->exists($criteria))
        {
            $data = Users::model()->find($criteria);
            return $data->email;
        } else {
            return "nvt";
        }
    }

    public function sendEmailWithNewPassword($model, $newWachtwoord)
    {    
        $message = Yii::$app->mailer->compose('resendPassword', [
            'newMailUsers'=>$model->username,
            'newWachtwoord'=>$newWachtwoord,
        ])
            ->setSubject('Wachtwoord Hike-app')
            ->setFrom('noreply@biologenkatoor.nl')
            ->setTo($model->email);

        if($message->send()){
            return true;
        }
        return false;
    }

	/**
	* apply a hash on the password before we store it in the database
	*/
	public function afterValidate()
	{
		parent::afterValidate();
		if(!$this->hasErrors() && Yii::app()->controller->action->id != 'update'){
            $this->password = $this->hashPassword($this->password);
        }
	}
}