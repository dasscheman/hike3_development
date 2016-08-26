<?php

namespace app\models;

use app\models\Users;

use Yii;

/**
 * This is the model class for table "tbl_open_vragen_antwoorden".
 *
 * @property integer $open_vragen_antwoorden_ID
 * @property integer $open_vragen_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property string $antwoord_spelers
 * @property integer $checked
 * @property integer $correct
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property TblUsers $createUser
 * @property TblEventNames $event
 * @property TblGroups $group
 * @property TblUsers $updateUser
 * @property TblOpenVragen $openVragen
 */
class OpenVragenAntwoorden extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_open_vragen_antwoorden';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_vragen_ID', 'event_ID', 'group_ID', 'antwoord_spelers'], 'required'],
            [['open_vragen_ID', 'event_ID', 'group_ID', 'checked', 'correct', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['antwoord_spelers'], 'string', 'max' => 255],
            [   ['open_vragen_ID', 'group_ID'],
                'unique', 
                'targetAttribute' => ['open_vragen_ID', 'group_ID'], 
                'message' => Yii::t('app/error', 'This group already answered this question')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'open_vragen_antwoorden_ID' => Yii::t('app', 'Answer ID'),
            'open_vragen_ID' => Yii::t('app', 'Question ID'),
            'event_ID' => Yii::t('app', 'Event ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'antwoord_spelers' => Yii::t('app', 'Answer Players'),
            'checked' => Yii::t('app', 'Checked'),
            'correct' => Yii::t('app', 'Correct'),
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
    public function getOpenVragen()
    {
        return $this->hasOne(OpenVragen::className(), ['open_vragen_ID' => 'open_vragen_ID']);
    }
    
    /**
     * Check if actions are allowed. These checks are not only use in the controllers,
     * but also for the visability of the menu items.
     */
    function isActionAllowed($controller_id = null, $action_id = null, $model_id = null, $group_id = null)
    {
        if (Yii::$app->user->identity === null){
            return false;
        }
        $actionAllowed = parent::isActionAllowed($controller_id, $action_id, $model_id, $group_id);

        $hikeStatus = EventNames::getStatusHike(Yii::$app->user->identity->getSelectedEventID());
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->identity->getSelectedEventID());

        switch ($action_id) {
            case 'antwoordGoedOfFout':
                if (($hikeStatus == EventNames::STATUS_introductie OR
                    $hikeStatus == EventNames::STATUS_gestart) AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie AND
                    !OpenVragenAntwoorden::isAntwoordGecontroleerd($model_id)) {
                        $actionAllowed = true;
                }
            break;
            case 'viewControle':
                if (($hikeStatus == EventNames::STATUS_introductie OR
                    $hikeStatus == EventNames::STATUS_gestart) AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $actionAllowed = true;
                }
            break;
            case 'updateOrganisatie':
                if (($hikeStatus == EventNames::STATUS_introductie OR
                    $hikeStatus == EventNames::STATUS_gestart) AND
                    $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                        $actionAllowed = true;
                }
            break;
        }
        return $actionAllowed;
    }

    /**
     * Als een nieuwe record aangemaakt wordt dan moeten deze waarden gezet worden.
     * Ook bedenken wat er met het score veld moet gebeuren... Als deze toch gezet wordt moet
     * de score anders opgehaald worden.
     */
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($innsert))
        {
            return false;
        }

        if($this->isNewRecord)
        {
            $this->correct = 0;
            $this->checked = 0;
        }
        return true;
    }

    /**
     * Score ophalen voor een group.
     */
    public function getOpenVragenScore($event_id, $group_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition= 
            "group_ID = $group_id AND
            event_ID = $event_id AND
            checked  = 1 AND
            correct  = 1";
        $data = OpenVragenAntwoorden::findAll($criteria);
        $score = 0;
        foreach($data as $obj)
        {
            $score = $score + OpenVragen::getOpenVraagScore($obj->open_vragen_ID);
        }
        return $score;
    }

    /**
     * Check of een bepaalde vraag is beantwoord door een group, Retruns true of false
     */
    public function isQuestionUsed($vragen_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition="open_vragen_ID = $vragen_id";
        $data = OpenVragenAntwoorden::model()->find($criteria);
        if(isset($data->antwoord_spelers))
            {return true;}
        else
            {return(false);}
    }

    /**
     * Check of een bepaalde vraag is beantwoord door een gegeven group, Retruns JA of NEE
     */
    public function isVraagBeantwoord($event_id,
				      $group_id,
				      $vragen_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition="event_ID = $event_id AND
                              group_ID = $group_id AND
                              open_vragen_ID = $vragen_id";
        $data = OpenVragenAntwoorden::model()->find($criteria);
        if(isset($data->antwoord_spelers))
            {return('Ja');}
        else
            {return('Nee');}
    }

    /**
     * Check of een bepaalde vraag is gecontroleerd, Retruns JA of NEE
     * Als JA dan moet het niet meer mogelijk zijn om die vraag te
     * beantwoorden door een groep.
     */
    public function isVraagGecontroleerd($event_id,
                                        $group_id,
                                        $vragen_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition="event_ID = $event_id AND
                              group_ID = $group_id AND
                              open_vragen_ID = $vragen_id";
        $data = OpenVragenAntwoorden::model()->find($criteria);

        if(isset($data->checked) AND $data->checked == 1)
            {return('Ja');}
        else
            {return('Nee');}
    }

    public function isAntwoordGecontroleerd($event_id, $id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition="event_ID = $event_id AND
                              open_vragen_antwoorden_ID = $id";
        $data = OpenVragenAntwoorden::model()->findAll($criteria);
        if(isset($data->checked) AND $data->checked == 1)
            return true;
        else
            return false;
    }

    /**
     * Check of een bepaald antwoord goed is. Retruns JA of NEE
     */
    public function isVraagGoed($event_id,
				$group_id,
				$vragen_id)
    {
        $criteria = new CDbCriteria;
        $criteria->condition="event_ID = $event_id AND
                              group_ID = $group_id AND
                              open_vragen_ID = $vragen_id";
        $data = OpenVragenAntwoorden::model()->find($criteria);
        if(isset($data->correct) AND $data->correct == 1)
            {return('Ja');}
        else
            {return('Nee');}
    }
}
