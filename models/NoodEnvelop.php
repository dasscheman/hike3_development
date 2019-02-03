<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_nood_envelop".
 *
 * @property integer $nood_envelop_ID
 * @property string $nood_envelop_name
 * @property integer $event_ID
 * @property integer $route_ID
 * @property integer $nood_envelop_volgorde
 * @property string $coordinaat
 * @property string $opmerkingen
 * @property integer $score
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 * @property string $latitude
 * @property string $longitude
 * @property int $show_coordinates
 *
 * @property Users $createUser
 * @property EventNames $event
 * @property Route $route
 * @property Users $updateUser
 * @property OpenNoodEnvelop[] $OpenNoodEnvelops
 * @property Groups[] $groups
 */
class NoodEnvelop extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_nood_envelop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nood_envelop_name', 'event_ID', 'route_ID', 'opmerkingen', 'score'], 'required'],
            [['event_ID', 'route_ID', 'nood_envelop_volgorde', 'score', 'create_user_ID', 'update_user_ID', 'show_coordinates'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['nood_envelop_name', 'coordinaat'], 'string', 'max' => 255],
            [['opmerkingen'], 'string', 'max' => 1050],
            [['nood_envelop_name', 'event_ID', 'route_ID'], 'unique', 'targetAttribute' => ['nood_envelop_name', 'event_ID', 'route_ID']],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['route_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_ID' => 'route_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nood_envelop_ID' => Yii::t('app', 'Hint ID'),
            'nood_envelop_name' => Yii::t('app', 'Hint titel'),
            'event_ID' => Yii::t('app', 'Hike ID'),
            'route_ID' => Yii::t('app', 'Route ID'),
            'route_name' => Yii::t('app', 'Route titel'),
            'nood_envelop_volgorde' => Yii::t('app', 'Volgorde'),
            'coordinaat' => Yii::t('app', 'Coordinaten'),
            'show_coordinates' => Yii::t('app', 'Toon coordinaten aan speler wanneer hij deze hint opent'),
            'opmerkingen' => Yii::t('app', 'Opmerkingen'),
            'score' => Yii::t('app', 'Strafpunten'),
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
    public function getRoute()
    {
        return $this->hasOne(Route::className(), ['route_ID' => 'route_ID']);
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
    public function getOpenNoodEnvelops()
    {
        return $this->hasMany(OpenNoodEnvelop::className(), ['nood_envelop_ID' => 'nood_envelop_ID']);
    }

    /**
    * Retrieves the score of an post.
    */
    public function getNoodEnvelopScore($envelop_id)
    {
        $data = NoodEnvelop::model()->find('nood_envelop_ID =:envelop_id', array(':envelop_id' => $envelop_id));
        return isset($data->score) ?
            $data->score : 0;
    }

    public function getRouteIdOfEnvelop($envelop_id)
    {
        $data = NoodEnvelop::model()->find(
            'nood_envelop_ID =:envelop_id',
                          array(':envelop_id' => $envelop_id)
        );
        if (isset($data->route_ID)) {
            return $data->route_ID;
        } else {
            return false;
        }
    }

    public function setNewOrderForNoodEnvelop()
    {
        $max_order = NoodEnvelop::find()
            ->select('nood_envelop_volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('route_ID=:route_id')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':route_id' =>$this->route_ID,
                ]
            )
            ->max('nood_envelop_volgorde');
        if (empty($max_order)) {
            $this->nood_envelop_volgorde = 1;
        } else {
            $this->nood_envelop_volgorde = $max_order+1;
        }
    }

    /**
     * Score ophalen voor een group.
     */
    public function isHintOpenedByGroup()
    {
        $db = self::getDb();
        $group_id = $db->cache(function ($db) {
            return DeelnemersEvent::find()
                ->select('group_ID')
                ->where('event_ID =:event_id AND user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
                ->one();
        });

        $data = $db->cache(function ($db) use ($group_id) {
            return OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND nood_envelop_ID =:nood_envelop_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':nood_envelop_id' => $this->nood_envelop_ID])
            ->exists();
        });

        return $data;
    }

    /**
     * Score ophalen voor een group.
     */
    public function getHintOpenedByGroup()
    {
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id AND user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
            ->one();

        $data = OpenNoodEnvelop::find()
            ->where('event_ID =:event_id AND group_ID =:group_id AND nood_envelop_ID =:nood_envelop_id')
            ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':group_id' => $group_id->group_ID, ':nood_envelop_id' => $this->nood_envelop_ID])
            ->one();

        if ($data === null) {
            $data = new OpenNoodEnvelop;
        }

        return $data;
    }

    public function lowererOrderNumberExists($nood_envelop_ID)
    {
        $data = NoodEnvelop::findOne($nood_envelop_ID);
        $dataNext = NoodEnvelop::find()
            ->where('event_ID =:event_id AND nood_envelop_ID !=:id AND route_ID=:route_id AND nood_envelop_volgorde <=:order')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':id' => $data->nood_envelop_ID,
                ':route_id' => $data->route_ID,
                ':order' => $data->nood_envelop_volgorde])
            ->exists();

        if ($dataNext) {
            return true;
        }
        return false;
    }

    public function higherOrderNumberExists($nood_envelop_ID)
    {
        $data = NoodEnvelop::findOne($nood_envelop_ID);
        $dataNext = NoodEnvelop::find()
            ->where('event_ID =:event_id AND nood_envelop_ID !=:id AND route_ID=:route_id AND nood_envelop_volgorde >=:order')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':id' => $data->nood_envelop_ID,
                ':route_id' => $data->route_ID,
                ':order' => $data->nood_envelop_volgorde])
            ->exists();

        if ($dataNext) {
            return true;
        }
        return false;
    }
}
