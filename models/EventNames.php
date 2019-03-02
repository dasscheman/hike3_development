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
class EventNames extends HikeActiveRecord {

    const STATUS_opstart = 1;
    const STATUS_introductie = 2;
    const STATUS_gestart = 3;
    const STATUS_beindigd = 4;
    const STATUS_geannuleerd = 5;
    /**
    * @var mixed image the attribute for rendering the file input
    * widget for upload on the form
    */
    public $image_temp;
    private $_daterange;
    public $start_all_groups;
    public $start_time_all_groups;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_event_names';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['event_name', 'status'], 'required'],
            [['start_date', 'end_date', 'active_day', 'max_time', 'create_time', 'update_time', 'website', 'image_temp', 'daterange'], 'safe'],
            [['status', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['event_name', 'image', 'organisatie', 'website'], 'string', 'max' => 255],
//            [['image'], 'unsafe', 'on'=>'update'],
            [['image_temp'],'file', 'extensions'=>'jpg, gif, png, jpeg', 'maxSize'=>1024 * 1024 * 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'event_ID' => Yii::t('app', 'Hike ID'),
            'event_name' => Yii::t('app', 'Hike Naam'),
            'start_date' => Yii::t('app', 'Start datum'),
            'end_date' => Yii::t('app', 'Eind datum'),
            'status' => Yii::t('app', 'Status'),
            'active_day' => Yii::t('app', 'Hike dag'),
            'max_time' => Yii::t('app', 'Tijdslimiet'),
            'image' => Yii::t('app', 'Image'),
            'image_temp' => Yii::t('app', 'Update image'),
            'organisatie' => Yii::t('app', 'Organisatie'),
            'website' => Yii::t('app', 'Website'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    public function setDaterange(){
        $this->_daterange =  $this->start_date . ' - '. $this->end_date;
    }

    public function getDaterange(){
        return $this->_daterange;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuspuntens() {
        return $this->hasMany(Bonuspunten::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeelnemersEvents() {
        return $this->hasMany(DeelnemersEvent::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreateUser() {
        return $this->hasOne(Users::className(), ['id' => 'create_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser() {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups() {
        return $this->hasMany(Groups::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoodEnvelops() {
        return $this->hasMany(NoodEnvelop::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenNoodEnvelops() {
        return $this->hasMany(OpenNoodEnvelop::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragens() {
        return $this->hasMany(OpenVragen::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpenVragenAntwoordens() {
        return $this->hasMany(OpenVragenAntwoorden::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostPassages() {
        return $this->hasMany(PostPassage::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostens() {
        return $this->hasMany(Posten::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrs() {
        return $this->hasMany(Qr::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQrChecks() {
        return $this->hasMany(QrCheck::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimteTrail() {
        return $this->hasMany(TimteTrail::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailItem() {
        return $this->hasMany(TimeTrailItem::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimteTrailCheck() {
        return $this->hasMany(TimeTrailCheck::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes() {
        return $this->hasMany(Route::className(), ['event_ID' => 'event_ID']);
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions() {
        return [
            self::STATUS_opstart => Yii::t('app', 'Uitzetten'),
            self::STATUS_introductie => Yii::t('app', 'Introductie'),
            self::STATUS_gestart => Yii::t('app', 'Gestart'),
            self::STATUS_beindigd => Yii::t('app', 'Beëindigd'),
            self::STATUS_geannuleerd => Yii::t('app', 'Geannuleerd'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getStatusText() {
        $statusOptions = $this->statusOptions;
        if (isset($statusOptions[$this->status])) {
            return $statusOptions[$this->status];
        }
        return "unknown status ({$this->status})";
    }

    /**
     * @return string the status text display
     */
    public function getStatusText2($status) {
        $statusOptions = $this->statusOptions;
        return isset($statusOptions[$status]) ?
            $statusOptions[$status] : "unknown status ({$status})";
    }

    /**
     * De het veld active day wordt gezet afhankelijk van de status.
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->status != self::STATUS_gestart) {
                // Als de status anders dan 2 (opgestart) dan moet active day geleegd worden
                $this->max_time = NULL;
                $this->active_day = NULL;
            }

            return(true);
        }
        return(false);
    }

    /**
     * Retrieves a list of events
     * @return array an array of available events with status 'opstart'.
     */
    public function getEventsWithStatusOpstart() {
        $data = EventNames::findAll(['status' => EventNames::STATUS_opstart]);
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
     * Retrieves a list of events
     * @return array an array of available events with status 'gestart'.
     */
    public function getEventsWithStatusGestart() {
        $data = EventNames::findAll(['status' => EventNames::STATUS_gestart]);
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
     * Retrieves a list of events
     * @return array an array of all available events'.
     */
    public function getEventNameOptions() {
        $data = EventNames::findAll();
        $list = CHtml::listData($data, 'event_ID', 'event_name');
        return $list;
    }

    /**
     * Retrieves a event name
     */
    public function getEventName($event_id) {
        $db = self::getDb();
        $data = $db->cache(function ($db) use($event_id) {
            return EventNames::find()
                ->where('event_ID =:event_Id', [':event_Id' => $event_id])
                ->one();
        });

        if (isset($data->event_name)) {
            return $data->event_name;
        } else {
            return;
        }
    }

    /**
     * Returns de status of a hike.
     */
    public function getStatusHike($event_Id) {
        $db = self::getDb();
        $data = $db->cache(function ($db) use($event_Id) {
            return EventNames::find()
            ->where('event_ID =:event_Id', [':event_Id' => $event_Id])
            ->one();
        });
        if (isset($data->status)) {
            return $data->status;
        } else {
            return;
        }
    }

    /**
     * Returns de status of a hike.
     */
    public function getStartDate($event_Id) {
        $db = self::getDb();
        $data = $db->cache(function ($db) use($event_Id) {
            return EventNames::find()
            ->where('event_ID =:event_Id', [':event_Id' => $event_Id])
            ->one();
        });
        if (isset($data->status)) {
            return $data->start_date;
        } else {
            return;
        }
    }

    /**
     * Returns de status of a hike.
     */
    public function getEndDate($event_Id) {
        $db = self::getDb();
        $data = $db->cache(function ($db) use($event_Id) {
            return EventNames::find()
            ->where('event_ID =:event_Id', [':event_Id' => $event_Id])
            ->one();
        });
        if (isset($data->status)) {
            return $data->end_date;
        } else {
            return;
        }
    }

    public function maxTimeSet($event_Id) {
        $data = EventNames::find()
            ->where('event_ID =:event_Id', [':event_Id' => $event_Id])
            ->one();
        if (isset($data->max_time)) {
            return $data->max_time;
        } else {
            return false;
        }
    }

    /**
     * Returns de actieve dag.
     */
    public function getActiveDayOfHike() {
        $event_id = Yii::$app->user->identity->selected_event_ID;
        $db = self::getDb();
        $data = $db->cache(function ($db) use($event_id) {
            return EventNames::find()
            ->where('event_ID =:event_Id')
            ->params([':event_Id' => $event_id])
            ->one();
        });

        if (isset($data->status)) {
            if ($data->status === EventNames::STATUS_introductie) {
                return NULL;
            }
            return $data->active_day;
        }
        return FALSE;
    }

    public function determineNewHikeId() {
        if (EventNames::find()->exists()) {
            $data = EventNames::find()
                ->orderBy(['event_ID' => SORT_DESC])
                ->one();
            $newHikeId = $data->event_ID + 1;
        } else {
            $newHikeId = 1;
        }

        $newHikeIdOk = EventNames::checkNewHikeId($newHikeId);

        if ($newHikeIdOk) {
            return $newHikeId;
        } else {
            return NULL;
        }
    }

    public function checkNewHikeId($id) {
        if (EventNames::find()->where(['event_ID' => $id])->exists()) {
            return false;
        }

        if (DeelnemersEvent::find()->where(['event_ID' => $id])->exists()) {
            return false;
        }
        return true;
    }

    public function getDatesAvailable($include_intro = TRUE) {
        $event_id = Yii::$app->user->identity->selected_event_ID;

        $StartDate = EventNames::getStartDate($event_id);
        $EndDate = EventNames::getEndDate($event_id);
        $mainarr = array();
        $date = $StartDate;
        $count = 0;
        if($include_intro) {
            $mainarr[NULL] = Yii::t('app', 'Introduction');
        }
        while ($date <= $EndDate) {
            $mainarr[$date] = $date;
            $date = date('Y-m-d', strtotime($date. ' + 1 days'));
            $count++;
            // more then 10 days is unlikly, therefore break.
            if ($count == 10) {
                break;
            }
        }
        return $mainarr;
    }

    /**
    * Process upload of image
    *
    * @return mixed the uploaded image instance
    */
    public function uploadImage() {
        // get the uploaded file instance. for multiple file uploads
        // the following data will return an array (you may need to use
        // getInstances method)
        $image = UploadedFile::getInstance($this, 'image');

        // if no image was uploaded abort the upload
        if (empty($image)) {
            return false;
        }

        // store the source file name
        $this->image = $image->name;
        $ext = end((explode(".", $image->name)));

        // generate a unique file name
//        $this->avatar = Yii::$app->security->generateRandomString().".{$ext}";

        // the uploaded image instance
        return $image;
    }


    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getImageFile()
    {
        return isset($this->avatar) ? Yii::$app->params['uploadPath'] . $this->avatar : null;
    }


    public function resizeForReport($image, $name) {
        $maxsize = 170;
        // Content type
        //header('Content-Type: image/jpeg');
        // Get new dimensions
        list($width, $height) = getimagesize($image);
        if ($width >= $height) {
            $ratio = $width / $maxsize;
        } else {
            $ratio = $height / $maxsize;
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
