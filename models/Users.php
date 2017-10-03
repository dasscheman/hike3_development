<?php

namespace app\models;

use Yii;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

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
class Users extends AccessControl implements IdentityInterface {

    public $search_friends;
    public $password_repeat;
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_CREATE = 'create';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_UPDATE] = ['update'];
        $scenarios[self::SCENARIO_CREATE] = ['change-password', 'create'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_users';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'voornaam', 'achternaam', 'email'], 'required'],
            [['birthdate', 'last_login_time', 'create_time', 'update_time'], 'safe'],
            [['create_user_ID', 'update_user_ID', 'selected_event_ID'], 'integer'],

            [['search_friends'], 'string', 'min'=>3],
            [['username', 'voornaam', 'achternaam', 'organisatie', 'email',
                'password', 'macadres', 'authKey', 'accessToken'], 'string', 'max' => 255],
            ['voornaam', 'filter', 'filter' => 'trim'],
            ['username', 'unique', 'targetClass' => '\app\models\Users', 'message' => Yii::t('app', 'This username address has already been taken.')],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\Users', 'message' => Yii::t('app', 'This email address has already been taken.')],
            [['password_repeat','password'], 'required', 'on' => self::SCENARIO_CREATE],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
            [
                'birthdate',
                'date',
                'message' => Yii::t('app', '{attribute}: This is not a date!'),
                'format' => 'yyyy-MM-dd'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
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
            'selected_event_ID' => Yii::t('app', 'Selected hike'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'accessToken' => Yii::t('app', 'Access Token'),
            'search_friends' => Yii::t('app', 'Search'),
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->authKey = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if (isset($this->birthdate)) {
                $this->birthdate = Yii::$app->setupdatetime->storeFormat($this->birthdate, 'date');
            }
            return(true);
        }
        return(false);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens() {
        return $this->hasMany(Bonuspunten::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens0() {
        return $this->hasMany(Bonuspunten::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents() {
        return $this->hasMany(DeelnemersEvent::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents0() {
        return $this->hasMany(DeelnemersEvent::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEventsByUserID() {
        return $this->hasMany(DeelnemersEvent::className(), ['user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames() {
        return $this->hasMany(EventNames::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames0() {
        return $this->hasMany(EventNames::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists() {
        return $this->hasMany(FriendList::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists0() {
        return $this->hasMany(FriendList::className(), ['friends_with_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists1() {
        return $this->hasMany(FriendList::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendListsByUserId() {
        return $this->hasMany(FriendList::className(), ['user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups() {
        return $this->hasMany(Groups::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups0() {
        return $this->hasMany(Groups::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops() {
        return $this->hasMany(NoodEnvelop::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops0() {
        return $this->hasMany(NoodEnvelop::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops() {
        return $this->hasMany(OpenNoodEnvelop::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops0() {
        return $this->hasMany(OpenNoodEnvelop::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens() {
        return $this->hasMany(OpenVragen::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens0() {
        return $this->hasMany(OpenVragen::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens() {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens0() {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages() {
        return $this->hasMany(PostPassage::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages0() {
        return $this->hasMany(PostPassage::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens() {
        return $this->hasMany(Posten::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens0() {
        return $this->hasMany(Posten::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs() {
        return $this->hasMany(Qr::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs0() {
        return $this->hasMany(Qr::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks() {
        return $this->hasMany(QrCheck::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks0() {
        return $this->hasMany(QrCheck::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes() {
        return $this->hasMany(Route::className(), ['create_user_ID' => 'user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes0() {
        return $this->hasMany(Route::className(), ['update_user_ID' => 'user_ID']);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findByEmail($email) {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->user_ID;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
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
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function hashPassword($password) {
        return md5($password);
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $this->hashPassword($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->authKey = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * Retrieves a list of users
     * @return array an array of all available users'.
     */
    public function getUserNameOptions() {
        $data = Users::find()->all();
        $list = ArrayHelper::map($data, 'user_ID', 'username');
        return $list;
    }

    /**
     * Retrieves username
     */
    public function getUserName($user_id) {
        $data = Users::find('user_ID =:user_id', array(':user_id' => $user_id));
        if (isset($data->event_name)) {
            return $data->username;
        } else {
            return;
        }
    }

    public function getFullName()
    {
        return $this->voornaam.' '.$this->achternaam;
    }

    public function sendEmailNewAccount() {
        $message = Yii::$app->mailer->compose('newAccount', [
                'newMailUsers' => $this->email,
                'newWachtwoord' => $this->password,
            ])
            ->setSubject('Wachtwoord Kiwi.run')
            ->setFrom(Yii::$app->params['noreply_email'])
            ->setTo($this->email);

        if ($message->send()) {
            return true;
        }
        return false;
    }

    public function sendEmailWithNewPassword($NewPassword) {
        $message = Yii::$app->mailer->compose('resendPassword', [
                'newMailUsers' => $this->email,
                'newWachtwoord' => $NewPassword,
            ])
            ->setSubject('Wachtwoord Kiwi.run')
            ->setFrom(Yii::$app->params['noreply_email'])
            ->setTo($this->email);

        if ($message->send()) {
            return true;
        }
        return false;
    }

    /**
     * apply a hash on the password before we store it in the database
     */
    public function afterValidate() {
        parent::afterValidate();
        if (!$this->hasErrors() && Yii::$app->controller->action->id != 'update') {
            $this->password = $this->hashPassword($this->password);
        }
    }
}
