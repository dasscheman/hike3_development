<?php

namespace app\models;

use Yii;
use app\components\GeneralFunctions;
use Da\QrCode\QrCode;
use yii\helpers\Url;

/**
 * This is the model class for table "tbl_time_trail_item".
 *
 * @property integer $time_trail_item_ID
 * @property integer $time_trail_ID
 * @property string $time_trail_item_name
 * @property string $code
 * @property string $instruction
 * @property integer $event_ID
 * @property integer $volgorde
 * @property integer $score
 * @property string $max_time
 * @property string $create_time
 * @property integer $create_user_ID
 * @property string $update_time
 * @property integer $update_user_ID
 * @property string $latitude
 * @property string $longitude
 *
 * @property TblTimeTrailCheck[] $tblTimeTrailChecks
 * @property TblGroups[] $groups
 * @property TblUsers $createUser
 * @property TblEventNames $event
 * @property TblTimeTrail $timeTrail
 * @property TblUsers $updateUser
 */
class TimeTrailItem extends HikeActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_time_trail_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_ID', 'event_ID', 'volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['time_trail_item_name', 'code', 'instruction', 'event_ID', 'score'], 'required'],
            [['time_trail_ID', 'max_time', 'create_time', 'update_time'], 'safe'],
            [['latitude', 'longitude'], 'number'],
            [['time_trail_item_name', 'code', 'instruction'], 'string', 'max' => 255],
            [['code', 'event_ID'], 'unique', 'targetAttribute' => ['code', 'event_ID'], 'message' => 'The combination of Trail Item Code and Event  ID has already been taken.'],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['time_trail_ID'], 'exist', 'skipOnError' => true, 'targetClass' => TimeTrail::className(), 'targetAttribute' => ['time_trail_ID' => 'time_trail_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'time_trail_item_ID' => Yii::t('app', 'Time Trail Item  ID'),
            'time_trail_ID' => Yii::t('app', 'Time Trail  ID'),
            'time_trail_item_name' => Yii::t('app', 'Time Trail Item Name'),
            'code' => Yii::t('app', 'Trail Item Code'),
            'event_ID' => Yii::t('app', 'Event  ID'),
            'volgorde' => Yii::t('app', 'Time Trail Item Volgorde'),
            'score' => Yii::t('app', 'Score'),
            'instrction' => Yii::t('app', 'Instruction'),
            'max_time' => Yii::t('app', 'Max Time'),
            'create_time' => Yii::t('app', 'Create Time'),
            'create_user_ID' => Yii::t('app', 'Create User  ID'),
            'update_time' => Yii::t('app', 'Update Time'),
            'update_user_ID' => Yii::t('app', 'Update User  ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimeTrailChecks()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['time_trail_item_ID' => 'time_trail_item_ID']);
    }


    public function getTimeTrailChecksCount()
    {
        return $this->hasMany(TimeTrailCheck::className(), ['time_trail_item_ID' => 'time_trail_item_ID'])->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['group_ID' => 'group_ID'])->viaTable('tbl_time_trail_check', ['time_trail_item_ID' => 'time_trail_item_ID']);
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
    public function getTimeTrail()
    {
        return $this->hasOne(TimeTrail::className(), ['time_trail_ID' => 'time_trail_ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    /**
     * Get the previous item, returns null when not exist.
     * @return \yii\db\ActiveQuery
     */
    public function getPreviousItem()
    {
        return  TimeTrailItem::find()
           ->where('event_ID =:event_id AND time_trail_ID =:time_trail_ID AND volgorde <:volgorde')
            ->params([
                ':event_id' => $this->event_ID,
                ':time_trail_ID'  => $this->time_trail_ID,
                ':volgorde'  => $this->volgorde,
            ])
           ->orderBy(['volgorde' => SORT_DESC])
            ->one();
    }

    /**
     * Get the previous item, returns null when not exist.
     * @return \yii\db\ActiveQuery
     */
    public function getNextItem()
    {
        return  TimeTrailItem::find()
           ->where('event_ID =:event_id AND time_trail_ID =:time_trail_ID AND volgorde >:volgorde')
            ->params([
                ':event_id' => $this->event_ID,
                ':time_trail_ID'  => $this->time_trail_ID,
                ':volgorde'  => $this->volgorde,
            ])
           ->orderBy(['volgorde' => SORT_ASC])
           ->one();
    }

    /**
     * Get the checked item for current user. Returns NULL when not exist.
     */
    public function getTimeTrailItemCheckedByGroupCurrentUser()
    {
        $data = DeelnemersEvent::find()
            ->where('event_ID = :event_Id AND user_ID=:user_Id')
            ->params([':event_Id' => Yii::$app->user->identity->selected_event_ID, ':user_Id' => Yii::$app->user->identity->id])
            ->one();

        return $this->hasMany(TimeTrailCheck::className(), ['time_trail_item_ID' => 'time_trail_item_ID'])
            ->andWhere(['group_ID' => $data->group_ID])
            ->one();
    }

    public function setNewOrderForTimeTrailItem()
    {
        $max_order = TimeTrailItem::find()
            ->select('volgorde')
            ->where('event_ID=:event_id')
            ->andwhere('time_trail_ID=:time_trail_id')
            ->addParams(
                [
                    ':event_id' => $this->event_ID,
                    ':time_trail_id' =>$this->time_trail_ID,
                ]
            )
            ->max('volgorde');
        if (empty($max_order)) {
            // dd(empty($max_order));
            $this->volgorde = 1;
        } else {
            $this->volgorde = $max_order+1;
        }
    }


    public function setUniqueCodeForTimeTrailItem()
    {
        $uniqueCode = 99;

        while ($uniqueCode == 99) {
            $newcode = GeneralFunctions::randomString(22);
            $data = TimeTrailItem::find()
                ->where('code = :code')
                ->params([':code' => $newcode])
                ->exists();
            // if code niet bestaat dan wordt de nieuwe gegenereede code gebruikt
            if (!$data) {
                $uniqueCode = $newcode;
            }
        }
        $this->code = $uniqueCode;
    }

    public function lowererOrderNumberExists($time_trail_item_id)
    {
        $data = TimeTrailItem::findOne($time_trail_item_id);
        $dataNext = TimeTrailItem::find()
            ->where('event_ID =:event_id AND volgorde <:order AND time_trail_ID =:time_trail_id')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':order' => $data->volgorde,
                ':time_trail_id' => $data->time_trail_ID])
            ->orderBy('volgorde DESC')
            ->exists();

        if ($dataNext) {
            return true;
        }
        return false;
    }

    public function higherOrderNumberExists($time_trail_item_id)
    {
        $data = TimeTrailItem::findOne($time_trail_item_id);
        $dataNext = TimeTrailItem::find()
            ->where('event_ID =:event_id AND volgorde >:order AND time_trail_ID =:time_trail_id')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':order' => $data->volgorde,
                ':time_trail_id' => $data->time_trail_ID])
            ->orderBy(['volgorde' => SORT_ASC])
            ->exists();

        if ($dataNext) {
            return true;
        }
        return false;
    }

    public function qrcode()
    {
        $event_id = Yii::$app->user->identity->selected_event_ID;

        $link = Url::to(['time-trail-check/create', 'event_id' => $event_id, 'code' => $this->code], true);

        $qrCode = new QrCode($link);

        $qrCode->writeFile(Yii::$app->params['timetrail_code_path'] . $this->code . '.jpg');
    }
}
