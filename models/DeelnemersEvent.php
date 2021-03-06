<?php

namespace app\models;

use Yii;
use app\components\GeneralFunctions;

/**
 * This is the model class for table "tbl_deelnemers_event".
 *
 * @property integer $deelnemers_ID
 * @property integer $event_ID
 * @property integer $user_ID
 * @property integer $rol
 * @property integer $group_ID
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 * @property string $color
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Users $updateUser
 * @property Users $user
 */
class DeelnemersEvent extends HikeActiveRecord
{
    const ROL_organisatie=1;
    const ROL_post=2;
    const ROL_deelnemer=3;
    const ROL_toeschouwer=4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_deelnemers_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_ID', 'user_ID'], 'required'],
            [['event_ID', 'user_ID', 'rol', 'group_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['event_ID','create_time', 'update_time','color'], 'safe'],
            [['color'], 'string', 'max' => 255],
            [
                ['event_ID', 'user_ID'],
                'unique',
                'targetAttribute' => ['event_ID', 'user_ID'],
                'message' => Yii::t('app', 'This user is already added to this hike.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deelnemers_ID' => Yii::t('app', 'Player ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'user_ID' => Yii::t('app', 'Username'),
            'rol' => Yii::t('app', 'Role'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * De het veld active day wordt gezet afhankelijk van de status.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->color === null) {
                // Als er nog geen kleur is toegevoegd, dan moet deze bepaald en gezet worden.
                $generalfucntions = new GeneralFunctions;
                $colors = $generalfucntions->colors;
                foreach ($colors as $color) {
                    $data = DeelnemersEvent::find()
                        ->where('event_ID = :event_Id AND color=:color')
                        ->params([':event_Id' => Yii::$app->user->identity->selected_event_ID, ':color' => $color]);

                    if ($data->exists()) {
                        continue;
                    }
                    $this->color = $color;
                    return true;
                }

                $search_color = true;
                while ($search_color) {
                    $hexcolor = $generalfucntions->random_color();
                    $data = DeelnemersEvent::find()
                        ->where('event_ID = :event_Id AND color=:color')
                        ->params([':event_Id' => Yii::$app->user->identity->selected_event_ID, ':color' => $color]);
                    if ($data->exists()) {
                        continue;
                    }
                    $this->color = $color;
                    $search_color = false;
                    return true;
                }
            }

            return(true);
        }
        return(false);
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_ID']);
    }

    /**
     * De het veld event_ID wordt altijd gezet.
     */
    // You can only add
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (Yii::$app->controller->id !== 'event-names' and
                Yii::$app->controller->action->id !== 'create') {
                $this->event_ID = Yii::$app->user->identity->selected_event_ID;
            }
            return(true);
        }
        return(false);
    }

    /**
    * Retrieves een lijst met mogelijke rollen die een deelnemer tijdens een hike kan hebben
    * @return array an array of available rollen.
    */
    static public function getRolOptions()
    {
        return array(
            self::ROL_organisatie=>'Organisatie',
            self::ROL_post=>'Post',
            self::ROL_deelnemer=>'Deelnemer',
            self::ROL_toeschouwer=>'Toeschouwer',
        );
    }

    /**
    * Retrieves een lijst met mogelijke rollen die een deelnemer tijdens een hike kan hebben
    * @return array an array of available rollen.
    */
    public function getOrganisationRolOptions()
    {
        return array(
            self::ROL_organisatie=>'Organisatie',
            self::ROL_post=>'Post',
            self::ROL_toeschouwer=>'Toeschouwer',
        );
    }

    /**
    * @return string de rol text display
    */
    static public function getRolText($rol)
    {
        $rolOptions = self::getRolOptions();
        return isset($rolOptions[$rol]) ?
            $rolOptions[$rol] : "Onbekende rol";
    }

    /**
    * @return string de rol text display
    */
    public function getRolTextObj()
    {
        $rolOptions=$this->getRolOptions();
        return isset($rolOptions[$this->rol]) ?
            $rolOptions[$this->rol] : "Onbekende rol";
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function getRolOfCurrentPlayerCurrentGame()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return DeelnemersEvent::findOne([
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'user_ID' => Yii::$app->user->identity->id
        ]);
        });

        if (isset($data->rol)) {
            return $data->rol;
        }
        return false;
    }

    /**
     * @return all players which are organisation, post or toeschouwer.
     */
    public function getOrganisationCurrentGame()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            $model = DeelnemersEvent::find()
                ->where('event_ID =:event_id AND (rol =:organisatie OR rol =:post OR rol =:toeschouwer)')
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':organisatie' => DeelnemersEvent::ROL_organisatie,
                    ':post' => DeelnemersEvent::ROL_post,
                    ':toeschouwer' => DeelnemersEvent::ROL_toeschouwer
                ]);
            if ($model->exists()) {
                return $model->all();
            }
            return false;
        });
        return $data;
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function getRolOfCurrentPlayer($event_id)
    {
        $data = DeelnemersEvent::findOne([
            'event_ID' => $event_id,
            'user_ID' => Yii::$app->user->identity->id
        ]);

        if (isset($data->rol)) {
            return $data->getRolText($data->rol);
        }
        return false;
    }

    /**
     * @return de group van een speler tijdens een bepaalde hike
     */
    public function getGroupOfPlayer($even_id)
    {
        $data = DeelnemersEvent::find()
            ->where('event_ID = :event_Id AND user_ID=:user_Id')
            ->params([':event_Id' => $even_id, ':user_Id' => Yii::$app->user->identity->id])
            ->one();
        if (!isset($data->rol)) {
            return false;
        }

        if ($data->rol <> DeelnemersEvent::ROL_deelnemer ||
           !isset($data->group_ID)) {
            return false;
        }

        return $data->group_ID;
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function userSignedinInHike($event_Id, $user_Id ) {
        $data = DeelnemersEvent::model()->exists(
            'event_ID = :event_Id AND user_ID=:user_Id',
              array(':event_Id' => $event_Id,
                    ':user_Id' => $user_Id)
        );
        return $data;
    }

    public function getId($event_Id, $user_Id) {
        $data = DeelnemersEvent::model()->find(
            'event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id)
        );
        if (isset($data->deelnemers_ID)) {
            return $data->deelnemers_ID;
        }
        return;
    }

    /**
    * Retrieves a list of users
    * @return array an of available friendusers which are not subscribed to current event.'.
    */
    public function getFriendsForEvent()
    {
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_accepted])
                        ->addParams([':user_id' => Yii::$app->user->id]);

        $queryDeelnemersEvent = DeelnemersEvent::find();
        $queryDeelnemersEvent->select('user_ID')
            ->where('event_ID=:event_id')
            ->addParams([':event_id' => Yii::$app->user->identity->selected_event_ID]);


        $result = Users::find()
           // ->select('id,name')->asArray()
            ->where(['in', 'user.id', $queryFriendList])
            ->andwhere(['not in', 'user.id', $queryDeelnemersEvent])
            ->andWhere('ISNULL(blocked_at)')
            ->all();

        $arrayRestuls = \yii\helpers\ArrayHelper::map($result, 'user_ID', 'username');
        return $arrayRestuls;
    }

    public function sendMailInschrijving()
    {
        Yii::$app->mailer->compose('sendInschrijving', [
                'mailEventName' => $this->event->event_name,
                'mailUsersName' => $this->user->username,
                'mailUsersNameSender' => $this->createUser->username,
                'mailUsersEmailSender' => $this->createUser->email,
                'mailRol' => $this->rol,
                'mailRolText' => DeelnemersEvent::getRolText($this->rol),
                'mailGroupName' => $this->group->group_name,
            ])
            ->setFrom(Yii::$app->params["admin_email"])
            ->setTo($this->user->email)
            ->setSubject('Inschrijving Hike')
            ->send();
    }
}
