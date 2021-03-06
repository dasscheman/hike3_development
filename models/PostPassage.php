<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_post_passage".
 *
 * @property integer $posten_passage_ID
 * @property integer $post_ID
 * @property integer $event_ID
 * @property integer $group_ID
 * @property integer $gepasseerd
 * @property string $binnenkomst
 * @property string $vertrek
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Groups $group
 * @property Posten $post
 * @property Users $updateUser
 */
class PostPassage extends HikeActiveRecord
{
    const STATUS_not_passed = 0;
    const STATUS_passed = 1;

    public $group_name;
    public $post_name;
    public $date;
    public $score;
    public $username;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_post_passage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_ID', 'event_ID', 'group_ID', 'gepasseerd'], 'required'],
            [['post_ID', 'event_ID', 'group_ID', 'gepasseerd', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['binnenkomst', 'vertrek', 'create_time', 'update_time'], 'safe'],
            [
                ['post_ID', 'event_ID', 'group_ID'],
                'unique',
                'targetAttribute' => ['post_ID', 'event_ID', 'group_ID'],
                'message' => Yii::t('app', 'This group already checked in on this station.')]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'posten_passage_ID' => Yii::t('app', 'Checked Station ID'),
            'post_ID' => Yii::t('app', 'Station ID'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'group_ID' => Yii::t('app', 'Group ID'),
            'gepasseerd' => Yii::t('app', 'Passed'),
            'binnenkomst' => Yii::t('app', 'Arrival'),
            'vertrek' => Yii::t('app', 'Departure'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User ID'),
        ];
    }

    /**
     * De het veld event_ID wordt altijd gezet.
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->event_ID = Yii::$app->user->identity->selected_event_ID;
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
    public function getGroupName()
    {
        $db = self::getDb();
        $data = $db->cache(function ($db) {
            return $this->hasOne(Groups::className(), ['group_ID' => 'group_ID'])->one()->group_name;
        });
        return $data;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posten::className(), ['post_ID' => 'post_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * De velden score en gepasseerd worden gezet als er een nieuwe record aangemaakt wordt.
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->gepasseerd = 1;
            }
            return true;
        }
        return false;
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getStatusOptions()
    {
        return [
            self::STATUS_not_passed => Yii::t('app', 'Not passed'),
            self::STATUS_passed => Yii::t('app', 'Passed'),
        ];
    }

    /**
     * Returns de score voor het passeren van de posten voor een groep
     */
    public function getPostScore($group_id)
    {
        $data = PostPassage::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND gepasseerd =:status')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id, ':status' => self::STATUS_passed])
            ->all();

        $score = 0;
        foreach ($data as $item) {
            $score = $score + $item->post->score;
        }
        return $score;
    }

    public function isTimeLeftToday($group_id)
    {
        $dataEvent = EventNames::find()
            ->where('event_ID = :event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one();
        if (!isset($dataEvent->max_time)) {
            // When max_time is not set return true, because there is time left
            // when no max is set.
            return true;
        }

        if (PostPassage::getTimeLeftToday($group_id) > 0) {
            return true;
        }
        return false;
    }

    public function getTimeLeftToday($group_id)
    {
        $dataEvent = EventNames::find()
            ->where('event_ID = :event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one();

        $totalTime = PostPassage::getWalkingTimeToday($group_id);
        if ((strtotime("1970-01-01 $dataEvent->max_time UTC") - $totalTime) < 0) {
            return 0;
        }
        return strtotime("1970-01-01 $dataEvent->max_time UTC") - $totalTime;
    }

    public function getWalkingTimeToday($group_id)
    {
        $dataEvent = EventNames::find()
            ->where('event_ID = :event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->one();

        $queryPosten = Posten::find()
            ->select('post_ID')
            ->where('event_ID =:event_id')
            ->Params([':event_id' => Yii::$app->user->identity->selected_event_ID]);

        $queryPassage = PostPassage::find()
            ->where(['in', 'post_ID', $queryPosten])
            ->andwhere('group_ID =:group_id')
            ->Params([':group_id' => $group_id])
            ->orderBy('binnenkomst ASC');

        $db = self::getDb();
        $postPassagesData = $db->cache(function ($db) use ($queryPassage) {
            return $queryPassage->all();
        });

        $aantalPosten = $db->cache(function ($db) use ($queryPassage) {
            return $queryPassage->count();
        });
        $totalTime = 0;
        $timeLastStint = 0;
        $timeLeftLastPost = 0;
        $atPost = false;
        $count = 1;

        foreach ($postPassagesData as $obj) {
            if ($aantalPosten == 1 && (strtotime($obj->vertrek))) {
                // Als $aantalPosten 1 is dan is het de start post en moeten
                // we alleen naar de vertrektijd gebruiken.
                // De deelnemers zijn niet op een post, dus ze zijn nog aan het lopen.
                // Daarom moet de huidige tijd min de laatste vertrektijd van elkaar
                // afgetrokken worden en opgeteld worden bij totaltime.
                $timeLastStint = strtotime(date('Y-m-d H:i:s')) - strtotime($obj->vertrek);
                $totalTime = $totalTime + $timeLastStint;
            }

            if ($count > 1) {
                $to_time = strtotime($obj->binnenkomst);
                $from_time = strtotime($timeLeftLastPost);
                $timeLastStint = $to_time-$from_time;
                $totalTime = $totalTime + $timeLastStint;

                if ($count == $aantalPosten && (strtotime($obj->vertrek))) {

                    // Hier wordt de laatste post gecontroleerd.
                    // De deelnemers zijn niet op een post, dus ze zijn nog aan het lopen.
                    // Daarom moet de huidige tijd min de laatste vertrektijd van elkaar
                    // afgetrokken worden en opgeteld worden bij totaltime.
                    $timeLastStint = strtotime(date('Y-m-d H:i:s')) - strtotime($obj->vertrek);
                    $totalTime = $totalTime + $timeLastStint;
                }
            }

            $timeLeftLastPost = $obj->vertrek;
            $count++;
        }
        return $totalTime;
    }

    public function isGroupStarted($group_id)
    {
        $eventNames = new EventNAmes();
        $active_day = $eventNames->getActiveDayOfHike();
        if($active_day == null) {
            // Geen active dag geselecteerd, dus deze dag kan ook niet gestart zijn
            return false;
        }
        $posten = new Posten();
        $start_post_id = $posten->getStartPost($active_day);
        if($start_post_id == null) {
            // Er zijn geen posten aangemaakt voor deze. Dus de groep is gewoon gestart.
            return true;
        }
        $data = PostPassage::find()
            ->where('event_ID =:event_id AND post_ID =:post_id AND group_ID =:group_id')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':post_id' => $start_post_id,
                ':group_id' => $group_id
            ])
            ->one();

        if ($data and
           $data->vertrek < strtotime(date('Y-m-d H:i:s'))) {
            return true;
        }
        return false;
    }

    public function isPostPassedByGroup($group_id, $post_id)
    {
        return PostPassage::find()
            ->where('event_ID =:event_id AND post_ID =:post_id AND gepasseerd =:gepasseerd AND group_ID =:group_id')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':post_id' => $post_id,
                ':gepasseerd' => true,
                ':group_id' => $group_id
            ])
            ->exists();
    }

    public function isPostChechedOutByGroup($group_id, $post_id)
    {
        $data = PostPassage::find()
            ->where('event_ID =:event_id AND post_ID =:post_id AND gepasseerd =:gepasseerd AND group_ID =:group_id')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':post_id' => $post_id,
                ':gepasseerd' => true,
                ':group_id' => $group_id
            ])
            ->one();
        if ($data !== null &&
            $data->vertrek !== null) {
            return true;
        }
        return false;
    }

    public function determineAction($post_id, $group_id)
    {
        $eventNames = new EventNames;
        $active_day = $eventNames->getActiveDayOfHike();
        $data = Posten::findOne($post_id);
        $postPassage = PostPassage::find()
            ->where('post_ID =:post_id AND group_ID =:group_id')
            ->params([':post_id' => $post_id, ':group_id' => $group_id])
            ->exists();
        $posten = new Posten;
        $posten->getStartPost($active_day);
        if ($data->isStartPost() &&
            $data->date === $active_day &&
            !$this->isGroupStarted($group_id)) {
            return 'start';
        }

        if (!$data->isStartPost() &&
            $data->date === $active_day &&
            !$postPassage &&
            !$this->isPostPassedByGroup($group_id, $post_id)) {
            return 'checkin';
        }

        if ($data->date === $active_day &&
            PostPassage::isGroupStarted($group_id) &&
            $postPassage &&
            PostPassage::isPostPassedByGroup($group_id, $post_id) &&
            !PostPassage::isPostChechedOutByGroup($group_id, $post_id)
        ) {
            return 'checkout';
        }
        return false;
    }

    public function getActionTitle($action, $group_name)
    {
        if ($action === 'start') {
            return Yii::t('app', 'Start hike {groupname}', ['groupname' => $group_name]);
        }

        if ($action === 'checkin') {
            return Yii::t('app', 'Checkin {groupname}', ['groupname' => $group_name]);
        }

        if ($action === 'checkout') {
            return Yii::t('app', 'Checkout {groupname}', ['groupname' => $group_name]);
        }

        return false;
    }

    public function anyGroupScoredStation() {
        return PostPassage::find()
            ->where('event_ID =:event_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID])
            ->exists();
    }
}
