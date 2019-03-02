<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "tbl_route_track".
 *
 * @property int $route_track_ID
 * @property int $event_ID
 * @property string $name
 * @property string $elevation
 * @property string $latitude
 * @property string $longitude
 * @property int $timestamp
 * @property int $type
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Users $updateUser
 */
class RouteTrack extends HikeActiveRecord
{
    const TYPE_track = 1;
    const TYPE_waypoint = 2;
    const TYPE_route = 3;

    public $track_temp;
    public $wp_temp;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_route_track';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_ID'], 'required'],
            [['event_ID', 'type', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['elevation', 'latitude', 'longitude'], 'number'],
            [['create_time', 'update_time', 'gpx_temp'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
            [['track_temp'],'file', 'extensions'=>'gpx', 'maxSize'=>1024 * 1024 * 2],
            [['wp_temp'],'file', 'extensions'=>'gpx', 'maxSize'=>1024 * 1024 * 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'route_track_ID' => 'Route Track  ID',
            'event_ID' => 'Event  ID',
            'name' => 'Name',
            'elevation' => 'Elevation',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'timestamp' => 'Timestamp',
            'type' => 'Type',
            'create_time' => 'Create Time',
            'create_user_ID' => 'Create User  ID',
            'update_time' => 'Update Time',
            'update_user_ID' => 'Update User  ID',
        ];
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
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * Retrieves a list of statussen
     * @return array an array of available statussen.
     */
    public function getTypeOptions() {
        return [
            self::TYPE_track => Yii::t('app', 'Track'),
            self::TYPE_waypoint => Yii::t('app', 'Way point'),
            self::TYPE_route => Yii::t('app', 'Route'),
        ];
    }

    /**
     * @return string the status text display
     */
    public function getTypeText() {
        $typeOptions = $this->typeOptions;
        if (isset($typeOptions[$this->type])) {
            return $typeOptions[$this->type];
        }
        return "unknown type ({$this->type})";
    }

    public function importTrack($track, $name)
    {
        try {
            $dbTransaction = Yii::$app->db->beginTransaction();
            foreach ($track->trkseg->trkpt as $point) {
                $model = new RouteTrack;
                $model->type = RouteTrack::TYPE_track;
                $model->latitude = $point['lat'];
                $model->longitude = $point['lon'];
                $model->timestamp = Yii::$app->setupdatetime->storeFormat($point->time, 'datetime');

                if(isset($point->ele)){
                    $model->elevation = $point->ele;
                }
                if(isset($point->name)){
                    $model->name = $point->name;
                } else {
                    $model->name = $name;
                }
                $model->event_ID = Yii::$app->user->identity->selected_event_ID;
                if (!$model->save()) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not save this track type: ' . $type));
                    $dbTransaction->rollBack();
                    return;
                }
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Could not save this track: ' . $name . ' '.  $e));
        }
    }

    public function importPoints($track, $type)
    {
        try {
            $dbTransaction = Yii::$app->db->beginTransaction();
            foreach ($track as $point) {

                $model = new RouteTrack;
                $model->type = $type;
                $model->latitude = $point['lat'];
                $model->longitude = $point['lon'];
                $model->timestamp = Yii::$app->setupdatetime->storeFormat($point->time, 'datetime');
                if(isset($point->ele)){
                    $model->elevation = $point->ele;
                }
                if(isset($point->name)){
                    $model->name = (string) $point->name;
                }
                $model->event_ID = Yii::$app->user->identity->selected_event_ID;
                if (!$model->save()) {
                    Yii::$app->session->setFlash('warning', Yii::t('app', 'Could not save this track type: ' . $type));
                    foreach ($model->getErrors() as $error) {
                        Yii::$app->session->setFlash('error', Json::encode($error));
                    }
                    $dbTransaction->rollBack();
                    return;
                }
            }
            $dbTransaction->commit();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'Could not save this track: ' . $type . ' '.  $e));
        }
    }
}
