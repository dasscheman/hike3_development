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
            [['open_vragen_ID', 'event_ID', 'group_ID'], 'required'],
            [['open_vragen_ID', 'event_ID', 'group_ID', 'checked', 'correct', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['antwoord_spelers'], 'string', 'max' => 255],
            [   ['open_vragen_ID', 'group_ID'],
                'unique',
                'targetAttribute' => ['open_vragen_ID', 'group_ID'],
                'message' => Yii::t('app', 'This group already answered this question')]
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
     * Als een nieuwe record aangemaakt wordt dan moeten deze waarden gezet worden.
     * Ook bedenken wat er met het score veld moet gebeuren... Als deze toch gezet wordt moet
     * de score anders opgehaald worden.
     */
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert))
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
    public function getOpenVragenScore($group_id)
    {
        $data = OpenVragenAntwoorden::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND checked =:checked AND correct =:correct')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':group_id' => $group_id, ':checked' => TRUE, ':correct' => TRUE])
            ->all();

        $score = 0;
    	foreach($data as $item)
        {
            $score = $score + $item->openVragen->score;
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
