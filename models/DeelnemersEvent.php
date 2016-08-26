<?php

namespace app\models;

use Yii;

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
            [['create_time', 'update_time'], 'safe'],
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
            'user_ID' => Yii::t('app', 'User ID'),
            'rol' => Yii::t('app', 'Role'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /* Only the actions specific to the model DeelnemersEvents and to the controller Game are here defined.
     * Game does not have an model for itself.
     */
    function isActionAllowed($controller_id = null, $action_id = null, $model_id = null, $group_id = null)
    {
        if (!isset(Yii::$app->user->identity->selected_event_ID)) {
            return false;
        }

        $event_id = Yii::$app->user->identity->selected_event_ID;

        $actionAllowed = parent::isActionAllowed($controller_id, $action_id, $event_id, $model_id, $group_id);
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);
        if ($rolPlayer == DeelnemersEvent::ROL_deelnemer) {
            $group_id_of_player = DeelnemersEvent::model()->getGroupOfPlayer($event_id, Yii::$app->user->id);
        }

        if (isset($rolPlayer) && $controller_id == 'game'){
            if ($rolPlayer <= DeelnemersEvent::ROL_deelnemer &&
				$action_id == 'gameOverview') {
					$actionAllowed = true;
			}
			if ($action_id == 'groupOverview') {
				if ($rolPlayer <= DeelnemersEvent::ROL_post) {
					$actionAllowed = true;
				}
				if ($rolPlayer == DeelnemersEvent::ROL_deelnemer &&
					$group_id == $group_id_of_player &&
					($hikeStatus == EventNames::STATUS_gestart ||
					$hikeStatus == EventNames::STATUS_introductie)) {
					$actionAllowed = true;
				}
				if ($rolPlayer == DeelnemersEvent::ROL_deelnemer &&
					$hikeStatus == EventNames::STATUS_beindigd) {
					$actionAllowed = true;
				}
			}
		}

		//Startup overview is only allowed when player is organisation
        if (isset($rolPlayer) && $controller_id === 'startup' && $rolPlayer == DeelnemersEvent::ROL_organisatie) {
            if (in_array($action_id, array('startupOverview'))) {
					$actionAllowed = true;
			}
        }
		return $actionAllowed;
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_ID' => 'user_ID']);
    }
    
    /**
    * Retrieves een lijst met mogelijke rollen die een deelnemer tijdens een hike kan hebben
    * @return array an array of available rollen.
    */
    public function getRolOptions()
    {
        return array(
            self::ROL_organisatie=>'Organisatie',
            self::ROL_post=>'Post',
            self::ROL_deelnemer=>'Deelnemer',
            self::ROL_toeschouwer=>'Toeschouwer',
        );
    }

    /**
    * @return string de rol text display
    */
    public function getRolText($rol)
    {
        $rolOptions=$this->getRolOptions();
        return isset($rolOptions[$rol]) ?
            $rolOptions[$rol] : "Onbekende rol";
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function getRolOfPlayer($user_Id)
    {
//        $data = DeelnemersEvent::findOne([['event_ID' => Yii::$app->user->identity->selected_event_ID],['user_ID' => $user_Id]]);
//        if(isset($data->rol))
//        {
//            return $data->rol;
//        }

        return;
    }

    /**
     * @return de group van een speler tijdens een bepaalde hike
     */
    public function getGroupOfPlayer(	$event_Id,
                                        $user_Id)
    {
        $data = DeelnemersEvent::model()->find('event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id));
        if(!isset($data->rol))
        {
            return;
        }

        if($data->rol<>DeelnemersEvent::ROL_deelnemer or
           !isset($data->group_ID))
        {
            return;
        }

        return $data->group_ID;
    }

    /**
     * @return de rol van een speler tijdens een bepaalde hike
     */
    public function userSignedinInHike($event_Id,
                                        $user_Id)
    {
        $data = DeelnemersEvent::model()->exists('event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id));
        return $data;
    }

    public function getId( $event_Id,
                            $user_Id)
    {
        $data = DeelnemersEvent::model()->find('event_ID = :event_Id AND user_ID=:user_Id',
                                            array(':event_Id' => $event_Id,
                                                  ':user_Id' => $user_Id));
        if(isset($data->deelnemers_ID)) {
            return $data->deelnemers_ID;
        }
        return;
    }
}