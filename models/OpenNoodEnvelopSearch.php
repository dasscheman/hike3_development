<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblOpenNoodEnvelop;

/**
 * TblOpenNoodEnvelopSearch represents the model behind the search form about `app\models\TblOpenNoodEnvelop`.
 */
class TblOpenNoodEnvelopSearch extends TblOpenNoodEnvelop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_nood_envelop_ID', 'nood_envelop_ID', 'event_ID', 'group_ID', 'opened', 'create_user_ID', 'update_user_ID'], 'integer'],
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
        $query = TblOpenNoodEnvelop::find();

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
            'open_nood_envelop_ID' => $this->open_nood_envelop_ID,
            'nood_envelop_ID' => $this->nood_envelop_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'opened' => $this->opened,
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
//	public function search()
//	{
//		// Warning: Please modify the following code to remove attributes that
//		// should not be searched.
//
//		$criteria=new CDbCriteria;
//
//		$criteria->compare('open_nood_envelop_ID',$this->open_nood_envelop_ID);
//		$criteria->compare('event_ID',$this->event_ID);
//		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
//		$criteria->compare('group_ID',$this->group_ID);
//		$criteria->compare('opened',$this->opened);
//		$criteria->compare('create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));
//	}
//

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchOpened($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->with=array('noodEnvelop', 'group', 'createUser', 'noodEnvelop.route');
		$criteria->compare('open_nood_envelop_ID',$this->open_nood_envelop_ID);
		$criteria->compare('t.event_ID',$event_id);
		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
		$criteria->compare('group_ID',$this->group_ID);
		$criteria->compare('opened',$this->opened);
		$criteria->compare('t.create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);
		$criteria->compare('group.group_name', $this->group_name,true);

		$criteria->compare('noodEnvelop.nood_envelop_name', $this->nood_envelop_name,true);

		$criteria->compare('route.day_date', $this->day_date,true);
		$criteria->compare('route.route_name', $this->route_name,true);

		$criteria->compare('noodEnvelop.score', $this->score,true);
		$criteria->compare('createUser.username', $this->username,true);

		$sort = new CSort();
		$sort->attributes = array(
			//'defaultOrder'=>'t.create_time ASC',
			'group_name'=>array(
				'asc'=>'group.group_name',
				'desc'=>'group.group_name desc',
			),
			'nood_envelop_name'=>array(
				'asc'=>'noodEnvelop.nood_envelop_name',
				'desc'=>'noodEnvelop.nood_envelop_name desc',
			),
			'day_date'=>array(
				'asc'=>'route.day_date',
				'desc'=>'route.day_date desc',
			),
			'route_name'=>array(
				'asc'=>'route.route_name',
				'desc'=>'route.route_name desc',
			),
			'username'=>array(
				'asc'=>'createUser.username',
				'desc'=>'createUser.username desc',
			),
			'score'=>array(
				'asc'=>'noodEnvelop.score',
				'desc'=>'noodEnvelop.score desc',
			),
			'create_time'=>array(
				'asc'=>'t.create_time',
				'desc'=>'t.create_time asc',
			),
		);

	    return new CActiveDataProvider($this, array(
		    'criteria'=>$criteria,
			'sort'=>$sort
	    ));
	}

}
