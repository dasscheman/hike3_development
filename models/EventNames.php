<?php

namespace app\models;

use app\models\HikeActiveRecord;
use app\models\DeelnemersEvent;
use app\models\EventNames;
use Yii;

/**
 * This is the model class for table "tbl_event_names".
 *
 * @property integer $event_ID
 * @property string $event_name
 * @property string $start_date
 * @property string $end_date
 * @property integer $status
 * @property string $active_day
 * @property string $max_time
 * @property string $image
 * @property string $organisatie
 * @property string $website
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Bonuspunten[] $Bonuspuntens
 * @property DeelnemersEvent[] $DeelnemersEvents
 * @property Users $createUser
 * @property Users $updateUser
 * @property Groups[] $Groups
 * @property NoodEnvelop[] $NoodEnvelops
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 * @property OpenVragen[] $OpenVragens
 * @property OpenVragenAntwoorden[] $OpenVragenAntwoordens
 * @property PostPassage[] $PostPassages
 * @property Posten[] $Postens
 * @property Qr[] $Qrs
 * @property QrCheck[] $QrChecks
 * @property Route[] $Routes
 */
class EventNames extends HikeActiveRecord
{
    const STATUS_opstart=1;
    const STATUS_introductie=2;
    const STATUS_gestart=3;
    const STATUS_beindigd=4;
    const STATUS_geannuleerd=5;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_event_names';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_name', 'organisatie', 'status'], 'required'],
            [['start_date', 'end_date', 'active_day', 'max_time', 'create_time', 'update_time', 'organisatie', 'website'], 'safe'],
            [['status', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['event_name', 'image', 'organisatie', 'website'], 'string', 'max' => 255],
//            [['image'], 'unsafe', 'on'=>'update'],
//            [['image'],'file', 'types'=>'jpg, gif, png, jpeg', 'maxSize'=>1024 * 1024 * 2, 'tooLarge'=>'File has to be smaller than 2MB'],       
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_ID' => Yii::t('app', 'Hike ID'),
            'event_name' => Yii::t('app', 'Hike Name'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'status' => Yii::t('app', 'Status'),
            'active_day' => Yii::t('app', 'Active Day'),
            'max_time' => Yii::t('app', 'Max Time'),
            'image' => Yii::t('app', 'Image'),
            'organisatie' => Yii::t('app', 'Organisation'),
            'website' => Yii::t('app', 'Website'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }
    
    /* Only the actions specific to the model DeelnemersEvents and to the 
     * controller Game are here defined. Game does not have an model for itself.
     */
    function isActionAllowed($controller_id = null, 
                            $action_id = null, 
                            $model_id = null, 
                            $group_id = null)
    {
        if (Yii::$app->user->identity === null) {
            return false;
        }
        
        $actionAllowed = parent::isActionAllowed($controller_id, 
                                                $action_id, 
                                                $model_id, 
                                                $group_id);

        if(!isset(Yii::$app->user->identity->selected_event_ID)){
            return false;
        } 
        
        $event_id = Yii::$app->user->identity->selected_event_ID;
        $hikeStatus = EventNames::getStatusHike($event_id);
        $rolPlayer = DeelnemersEvent::getRolOfPlayer(Yii::$app->user->id);

        if ($action_id == 'changeStatus'){
            if (($hikeStatus == EventNames::STATUS_opstart or
                $hikeStatus == EventNames::STATUS_introductie or
                $hikeStatus == EventNames::STATUS_gestart) and
                $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                $actionAllowed = true;
            }
        }
        if ($action_id == 'changeDay'){
            if ($hikeStatus == EventNames::STATUS_gestart and
                $rolPlayer == DeelnemersEvent::ROL_organisatie) {
                $actionAllowed = true;
            }
        }
		return $actionAllowed;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens()
    {
        return $this->hasMany(Bonuspunten::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents()
    {
        return $this->hasMany(DeelnemersEvent::className(), ['event_ID' => 'event_ID']);
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
    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops()
    {
        return $this->hasMany(NoodEnvelop::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens()
    {
        return $this->hasMany(OpenVragen::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens()
    {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages()
    {
        return $this->hasMany(PostPassage::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens()
    {
        return $this->hasMany(Posten::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs()
    {
        return $this->hasMany(Qr::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks()
    {
        return $this->hasMany(QrCheck::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['event_ID' => 'event_ID']);
    }

    /**
    * Retrieves a list of statussen
    * @return array an array of available statussen.
    */
    public function getStatusOptions()
    {
        return [
            self::STATUS_opstart=>'Opstart',
            self::STATUS_introductie=>'Introductie',
            self::STATUS_gestart=>'Gestart',
            self::STATUS_beindigd=>'Beindigd',
            self::STATUS_geannuleerd=>'Geannuleerd',
        ];
    }

    /**
    * @return string the status text display
    */
    public function getStatusText()
    {
        $statusOptions=$this->statusOptions;
        if ( isset($statusOptions[$this->status]) ){
            return $statusOptions[$this->status];
        }
        return "unknown status ({$this->status})";
    }

    /**
    * @return string the status text display
    */
    public function getStatusText2($status)
    {
        $statusOptions=$this->statusOptions;
        return isset($statusOptions[$status]) ?
            $statusOptions[$status] : "unknown status ({$status})";
    }

    /**
     * De het veld active day wordt gezet afhankelijk van de status.
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->status<>self::STATUS_gestart)
            {
                // Als de status anders dan 2 (opgestart) dan moet active day geleegd worden
                $this->active_day = "";
                $this->max_time = null;
            }

            if($this->status == self::STATUS_introductie)
            {
                // Als de status 1 (introductie) dan moet avtive day introductie worden
                $this->active_day = "0000-00-00";
            }
            return(true);
        }
        return(false);
    }

    /**
    * Retrieves a list of events
    * @return array an array of available events with status 'opstart'.
    */
    public function getEventsWithStatusOpstart()
    {
        $data = EventNames::findAll(['status' => EventNames::STATUS_opstart]);
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
    * Retrieves a list of events
    * @return array an array of available events with status 'gestart'.
    */
    public function getEventsWithStatusGestart()
    {
        $data = EventNames::findAll(['status' => EventNames::STATUS_gestart]);
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
    * Retrieves a list of events
    * @return array an array of all available events'.
    */
    public function getEventNameOptions()
    {
        $data = EventNames::findAll();
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
    * Retrieves a event name
    */
    public function getEventName($event_Id)
    {
        $data = EventNames::find('event_ID =:event_Id', array(':event_Id' => $event_Id));
        if(isset($data->event_name))
            {return $data->event_name;}
        else
            {return;}
    }

    /**
     * Returns de status of a hike.
     */
    public function getStatusHike($event_Id)
    {
        $data = EventNames::find('event_ID =:event_Id', array(':event_Id' => $event_Id));
        if(isset($data->status))
            {return $data->status;}
        else
            {return;}
    }

    /**
     * Returns de status of a hike.
     */
    public function getStartDate($event_Id)
    {
        $data = EventNames::find('event_ID =:event_Id', array(':event_Id' => $event_Id));
        if(isset($data->status))
            {return $data->start_date;}
        else
            {return;}
    }

    /**
     * Returns de status of a hike.
     */
    public function getEndDate($event_Id)
    {
        $data = EventNames::find('event_ID =:event_Id', array(':event_Id' => $event_Id));
        if(isset($data->status))
            {return $data->end_date;}
        else
            {return;}
    }

    public function maxTimeSet($event_Id){
        $data = EventNames::find('event_ID =:event_Id', array(':event_Id' => $event_Id));
        if(isset($data->max_time))
            {return $data->max_time;}
        else
            {return false;}
    }

    /**
     * Returns de actieve dag.
     */
    public function getActiveDayOfHike($event_id)
    {
        $data = EventNames::find('event_ID =:event_id', array(':event_id' => $event_id));
        if(isset($data->active_day))
            {return $data->active_day;}
        else
            {return;}
    }

    public function determineNewHikeId()
    {
        if (EventNames::find()->exists()) {
            $data = EventNames::find()
                ->orderBy(['event_ID' => SORT_DESC])
                ->one();
            $newHikeId = $data->event_ID+1;
        } else {
            $newHikeId = 1;}

        $newHikeIdOk=EventNames::checkNewHikeId($newHikeId);

        if($newHikeIdOk)
        {
            return $newHikeId;
        } else {
            return NULL;
        }
    }

    public function checkNewHikeId($id)
    {
        if(EventNames::find()->where(['event_ID' => $id])->exists())
        {
            return false;
        }

        if(DeelnemersEvent::find()->where(['event_ID' => $id])->exists())
        {
            return false;
        }
        return true;
    }

    public function getDatesAvailable($event_Id)
    {
        $StartDate = EventNames::getStartDate($event_Id);
        $EndDate = EventNames::getEndDate($event_Id);
        $mainarr = array();
        $date = $StartDate;
        $count = 0;
        while($date <= $EndDate)
        {
            $a = strptime($date, '%Y-%m-%d');
            $timestamp = mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
            //$timestamp = strtotime($date);
            $mainarr[$timestamp] = $date;
            $date++;
            $count++;
            // more then 10 days is unlikly, therefore break.
            if ($count == 10) {
                    break;
            }
        }
        return $mainarr;
    }

    public function resizeForReport($image, $name)
    {
        $maxsize = 170;
        // Content type
        //header('Content-Type: image/jpeg');

        // Get new dimensions
        list($width, $height) = getimagesize($image);
        if ($width >= $height) {
                $ratio = $width/$maxsize;
        } else {
                $ratio = $height/$maxsize;
        }
        $new_width = $width / $ratio;
        $new_height = $height / $ratio;

        // Resample
        $image_p = imagecreatetruecolor($new_width, $new_height);
        $timage = imagecreatefromjpeg($image);
        imagecopyresampled($image_p, $timage, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Output
        imagejpeg($image_p, 'images/event_images/qrreport/' . $name, 100);
    }
}
