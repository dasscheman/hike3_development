<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblQrCheck;

/**
 * TblQrCheckSearch represents the model behind the search form about `app\models\TblQrCheck`.
 */
class TblQrCheckSearch extends TblQrCheck
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qr_check_ID', 'qr_ID', 'event_ID', 'group_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TblQrCheck::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'qr_check_ID' => $this->qr_check_ID,
            'qr_ID' => $this->qr_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
    
    
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
//	public function search($event_id)
//	{
//		// Warning: Please modify the following code to remove attributes that
//		// should not be searched.
//
//		$criteria=new CDbCriteria;
//
//		$criteria->with=array('qr', 'group', 'createUser');
//		$criteria->compare('qr_check_ID',$this->qr_check_ID);
//		$criteria->compare('qr_ID',$this->qr_ID);
//		$criteria->compare('t.event_ID',$event_id);
//		$criteria->compare('group_ID',$this->group_ID);
//		$criteria->compare('t.create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		$criteria->compare('group.group_name', $this->group_name,true);
//
//		$criteria->compare('qr.qr_name', $this->qr_name,true);
//		$criteria->compare('qr.score', $this->score,true);
//		$criteria->compare('createUser.username', $this->username,true);
//
//		$sort = new CSort();
//		$sort->attributes = array(
//			//'defaultOrder'=>'t.create_time ASC',
//			'group_name'=>array(
//				'asc'=>'group.group_name',
//				'desc'=>'group.group_name desc',
//			),
//			'qr_name'=>array(
//				'asc'=>'qr.qr_name',
//				'desc'=>'qr.qr_name desc',
//			),
//			'score'=>array(
//				'asc'=>'qr.score',
//				'desc'=>'qr.score desc',
//			),
//			'username'=>array(
//				'asc'=>'createUser.username',
//				'desc'=>'createUser.username desc',
//			),
//			'create_time'=>array(
//				'asc'=>'t.create_time',
//				'desc'=>'t.create_time asc',
//			),
//		);
//
//	    return new CActiveDataProvider($this, array(
//		    'criteria'=>$criteria,
//			'sort'=>$sort
//	    ));
//	}

}
