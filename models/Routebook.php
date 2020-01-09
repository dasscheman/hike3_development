<?php

namespace app\models;

use Yii;
use app\components\GeneralFunctions;

/**
 * This is the model class for table "tbl_routebook".
 *
 * @property int $routebook_ID
 * @property int $event_ID
 * @property int $route_ID
 * @property string $tekst
 * @property string $create_time
 * @property int $create_user_ID
 * @property string $update_time
 * @property int $update_user_ID
 *
 * @property User $createUser
 * @property EventNames $event
 * @property Route $route
 * @property User $updateUser
 */
class Routebook extends \yii\db\ActiveRecord
{
    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_routebook';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_ID', 'route_ID'], 'required'],
            [['event_ID', 'route_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['create_time', 'update_time'], 'safe'],
            [['tekst'], 'string', 'max' => 2555],
            [['create_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['create_user_ID' => 'id']],
            [['event_ID'], 'exist', 'skipOnError' => true, 'targetClass' => EventNames::className(), 'targetAttribute' => ['event_ID' => 'event_ID']],
            [['route_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Route::className(), 'targetAttribute' => ['route_ID' => 'route_ID']],
            [['update_user_ID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['update_user_ID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'routebook_ID' => 'Routebook ID',
            'event_ID' => 'Event ID',
            'route_ID' => 'Route ID',
            'tekst' => 'Tekst',
            'create_time' => 'Create Time',
            'create_user_ID' => 'Create User ID',
            'update_time' => 'Update Time',
            'update_user_ID' => 'Update User ID',
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
    public function getRoute()
    {
        return $this->hasOne(Route::className(), ['route_ID' => 'route_ID'])
            ->orderBy(['tbl_route.start_datetime' => SORT_ASC, 'tbl_route.end_datetime' => SORT_ASC ]);
;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdateUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'update_user_ID']);
    }

    public function getRoutebook()
    {
      // dd( Yii::$app->setupdatetime->storeFormat(time(), 'datetime'));
        $query = $this::find()
            ->joinWith(['route'])
            ->where('tbl_routebook.event_ID=:event_id')
            ->andWhere(['or',
                 ['start_datetime' => null ],
                 [ '<', 'start_datetime', Yii::$app->setupdatetime->storeFormat(time(), 'datetime')]
            ])
            ->andWhere(['or',
                 ['end_datetime'=> null],
                 [ '>', 'end_datetime', Yii::$app->setupdatetime->storeFormat(time(), 'datetime')]
            ])
            ->orderby('tbl_route.start_datetime')
            ->addParams([
              'event_id' => Yii::$app->user->identity->selected_event_ID,
            ]);
        return $query;
    }

    public function getUniquePath($filename)
    {
          $uniqueName = $filename . '-' . GeneralFunctions::randomString(6);
          $path = Yii::$app->params['routebookimages'] . $uniqueName . '.jpg';

          if(file_exists($path)) {
              $uniqueName = $this->getUniquePath($filename);
          }
          return $uniqueName;
    }
}
