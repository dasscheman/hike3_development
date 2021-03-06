<?php

namespace app\models;

use Yii;
//use yii\helpers\Security;
//use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;
use app\models\DeelnemersEvent;
use app\models\Profile;
use yii\helpers\Json;
use dektrium\user\models\User as BaseUser;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $voornaam
 * @property string $achternaam
 * @property string $organisatie
 * @property string $birthdate
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $confirmed_at
 * @property string $unconfirmed_email
 * @property integer $blocked_at
 * @property string $registration_ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $flags
 * @property integer $selected_event_ID
 * @property integer $last_login_at
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Profile $profile
 * @property SocialAccount[] $socialAccounts
 * @property TblBonuspunten[] $tblBonuspuntens
 * @property TblBonuspunten[] $tblBonuspuntens0
 * @property TblEventNames[] $tblEventNames
 * @property TblEventNames[] $tblEventNames0
 * @property TblFriendList[] $tblFriendLists
 * @property TblFriendList[] $tblFriendLists0
 * @property TblFriendList[] $tblFriendLists1
 * @property TblFriendList[] $tblFriendLists2
 * @property User[] $users
 * @property User[] $friendsWithUsers
 * @property TblGroups[] $tblGroups
 * @property TblGroups[] $tblGroups0
 * @property TblNoodEnvelop[] $tblNoodEnvelops
 * @property TblNoodEnvelop[] $tblNoodEnvelops0
 * @property TblOpenNoodEnvelop[] $tblOpenNoodEnvelops
 * @property TblOpenNoodEnvelop[] $tblOpenNoodEnvelops0
 * @property TblOpenVragen[] $tblOpenVragens
 * @property TblOpenVragen[] $tblOpenVragens0
 * @property TblOpenVragenAntwoorden[] $tblOpenVragenAntwoordens
 * @property TblOpenVragenAntwoorden[] $tblOpenVragenAntwoordens0
 * @property TblPostPassage[] $tblPostPassages
 * @property TblPostPassage[] $tblPostPassages0
 * @property TblPosten[] $tblPostens
 * @property TblPosten[] $tblPostens0
 * @property TblQr[] $tblQrs
 * @property TblQr[] $tblQrs0
 * @property TblQrCheck[] $tblQrChecks
 * @property TblQrCheck[] $tblQrChecks0
 * @property TblRoute[] $tblRoutes
 * @property TblRoute[] $tblRoutes0
 * @property TblTimeTrail[] $tblTimeTrails
 * @property TblTimeTrail[] $tblTimeTrails0
 * @property TblTimeTrailCheck[] $tblTimeTrailChecks
 * @property TblTimeTrailCheck[] $tblTimeTrailChecks0
 * @property TblTimeTrailItem[] $tblTimeTrailItems
 * @property TblTimeTrailItem[] $tblTimeTrailItems0
 * @property Token[] $tokens
 */

class Users extends BaseUser
{
    public $search_friends;
    public $password_repeat;
    const SCENARIO_REGISTER = 'register';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['password_repeat','password', 'username', 'email'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voornaam', 'achternaam', 'email'], 'required', 'on' =>  ['connect', 'create', 'update'],],
            [['confirmed_at', 'blocked_at', 'created_at', 'updated_at', 'flags', 'last_login_at',
                'create_user_ID', 'update_user_ID', 'selected_event_ID'], 'integer'],
            [['birthdate', 'organisatie', 'voornaam', 'achternaam', 'email', 'create_time', 'update_time', 'password_repeat','password', 'created_at', 'updated_at'], 'safe'],
            [['search_friends'], 'string', 'min'=>3],
            [['username', 'email', 'unconfirmed_email'], 'string', 'max' => 255],
            ['password_hash', 'string', 'max' => 60],
            ['password_hash', 'string', 'min' => 6],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            ['username', 'unique', 'targetClass' => '\app\models\Users', 'message' => Yii::t('app', 'This username address has already been taken.')],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\Users', 'message' => Yii::t('app', 'This email address has already been taken.')],
            [['username','password', 'email'], 'required', 'on' => self::SCENARIO_REGISTER],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match", 'on' => self::SCENARIO_REGISTER],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'confirmed_at' => Yii::t('app', 'Confirmed At'),
            'unconfirmed_email' => Yii::t('app', 'Unconfirmed Email'),
            'blocked_at' => Yii::t('app', 'Blocked At'),
            'registration_ip' => Yii::t('app', 'Registration Ip'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'flags' => Yii::t('app', 'Flags'),
            'selected_event_ID' => Yii::t('app', 'Selected hike'),
            'last_login_at' => Yii::t('app', 'Last Login At'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }


    /**
    * Attaches the timestamp behavior to update our create and update times
    */
    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'create_user_ID',
                'updatedByAttribute' => 'update_user_ID',
            ],
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['create_time', 'update_time', 'created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['update_time', 'updated_at'],
                ],
                'value' => function () {
                    return \Yii::$app->setupdatetime->storeFormat(time(), 'datetime');
                },
            ],
        ];
    }

    /**
     * Role wordt gezet voor user on create.
     */
    public function afterSave( $insert, $changedAttributes )
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {
            Yii::$app->db->createCommand()->insert('auth_assignment',
            [
                'user_id' =>  $this->id,
                'item_name' => 'gebruiker'
            ])->execute();
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens0()
    {
        return $this->hasMany(Bonuspunten::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEventsCreateUser()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents0()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEventsByUserID()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents()
    {
        return $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolUserForEvent()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            $deelnemer = $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID]);

            if ($deelnemer->exists()) {
                return $deelnemer->one()->rol;
            }
            return false;
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColorUserForEvent()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            $deelnemer = $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
                ->where('event_ID =:event_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID]);

            if ($deelnemer->exists()) {
                return $deelnemer->one()->color;
            }
            return false;
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupUserForEvent()
    {
        return $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one()
            ->group_ID;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusForEvent()
    {
        $status = $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one()
            ->event
            ->status;
        return $status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveDayForEvent()
    {
        $status = $this->hasOne(DeelnemersEvent::className(), ['user_ID' => 'id'])
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one()
            ->event
            ->active_day;
        return $status;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames()
    {
        return $this->hasMany(EventNames::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventNames0()
    {
        return $this->hasMany(EventNames::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists()
    {
        return $this->hasMany(FriendList::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists0()
    {
        return $this->hasMany(FriendList::className(), ['friends_with_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendLists1()
    {
        return $this->hasMany(FriendList::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendListsByUserId()
    {
        return $this->hasMany(FriendList::className(), ['user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_ID'])->viaTable('tbl_friend_list', ['friends_with_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFriendsWithUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'friends_with_user_ID'])->viaTable('tbl_friend_list', ['user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups0()
    {
        return $this->hasMany(Groups::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops0()
    {
        return $this->hasMany(NoodEnvelop::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops0()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens()
    {
        return $this->hasMany(OpenVragen::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens0()
    {
        return $this->hasMany(OpenVragen::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens0()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages0()
    {
        return $this->hasMany(PostPassage::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens()
    {
        return $this->hasMany(Posten::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens0()
    {
        return $this->hasMany(Posten::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs()
    {
        return $this->hasMany(Qr::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs0()
    {
        return $this->hasMany(Qr::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks0()
    {
        return $this->hasMany(QrCheck::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes0()
    {
        return $this->hasMany(Route::className(), ['update_user_ID' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrails()
    {
        return $this->hasMany(TimeTrail::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrails0()
    {
        return $this->hasMany(TimeTrail::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailChecks()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailChecks0()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItems()
    {
        return $this->hasMany(TimeTrailItem::className(), ['create_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItems0()
    {
        return $this->hasMany(TimeTrailItem::className(), ['update_user_ID' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
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
     * Finds user by email
     *
     * @param  string      $email
     * @return static|null
     */
    public static function findUserByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Retrieves a list of users
     * @return array an array of all available users'.
     */
    public function getUserNameOptions()
    {
        $data = Users::find()
            ->andWhere('ISNULL(blocked_at)')
            ->all();
        $list = ArrayHelper::map($data, 'user_ID', 'username');
        return $list;
    }

    /**
     * Retrieves username
     */
    public function getUserName($user_id)
    {
        $data = Users::find('user_ID =:user_id', array(':user_id' => $user_id));
        if (isset($data->event_name)) {
            return $data->username;
        } else {
            return;
        }
    }

    public function getFullName()
    {
        if(!empty($this->voornaam) && !empty($this->achternaam)) {
            return $this->voornaam.' '.$this->achternaam;
        }

        if(!empty($this->voornaam)) {
            return $this->voornaam;
        }

        if(!empty($this->achternaam)) {
            return $this->achternaam;
        }
        return $this->username;
    }

    /**
     *
     */
    public function setSelectedEventID()
    {
        if (null !== Yii::$app->request->get('event_id') and
            Yii::$app->request->get('event_id') !== Yii::$app->user->identity->selected_event_ID) {
            // When a qr code is scanned the, event_ID is passed in the GET.
            // Because all checks are based on the selected_event_ID we must
            // if the GET and the selected have the same ID, if not check the GET
            // event_ID. When okey, set this id.
            // This is done here in case it is usedby other models.
            $exists = DeelnemersEvent::find()
                ->where('user_ID=:user_id AND event_ID=:event_id')
                ->addParams([
                    ':user_id' => Yii::$app->user->identity->id,
                    ':event_id' => Yii::$app->request->get('event_id')
                ])
                ->exists();

            if ($exists) {
                Yii::$app->user->identity->selected_event_ID = (int) Yii::$app->request->get('event_id');
                Yii::$app->user->identity->save();
            } else {
                // This user is not added to the event_id in the GET.
                return false;
            }
        }

        if (!isset(Yii::$app->user->identity->selected_event_ID)) {
            // Select the event_ID which is most recently modified.
            $selected = DeelnemersEvent::find()
                ->where('user_ID=:user_id')
                ->addParams([':user_id' => Yii::$app->user->identity->id])
                ->orderBy(['update_time' => SORT_DESC]);

            if (!$selected->exists()) {
                return false;
            }
            Yii::$app->user->identity->selected_event_ID = (int) $selected->one()->event_ID;
            Yii::$app->user->identity->save();
        }
    }

    public function addUserToEvent($group, $player)
    {
        $inschrijving = $this->getDeelnemersEvents()->one();
        $sendmail = false;
        if (!$inschrijving) {
            //inschrijving bestaat niet dus we maken een nieuwe aan:
            $inschrijving = new DeelnemersEvent;
            $inschrijving->event_ID = Yii::$app->user->identity->selected_event_ID;
            // Deze gebruiker was nog niet toegevoegd aandeze groep,
            // daarom zenden we deze mensen een mail.
            $sendmail = true;
        }
        // Voor nu schrijven we alles over. De aanname is dat in de
        // selectie alleen de juiste namen zichtbaar zijn.
        $inschrijving->group_ID = $group;
        $inschrijving->user_ID = $player;
        $inschrijving->rol = DeelnemersEvent::ROL_deelnemer;
        if (!$inschrijving->save()) {
            foreach ($inschrijving->getErrors() as $error) {
                Yii::$app->session->setFlash('error', Json::encode($error));
            }
            return false;
        }
        if ($sendmail) {
            $inschrijving->sendMailInschrijving();
        }
        return true;
    }
}
