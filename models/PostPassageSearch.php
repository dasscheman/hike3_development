<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblPostPassage;

/**
 * TblPostPassageSearch represents the model behind the search form about `app\models\TblPostPassage`.
 */
class TblPostPassageSearch extends TblPostPassage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posten_passage_ID', 'post_ID', 'event_ID', 'group_ID', 'gepasseerd', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['binnenkomst', 'vertrek', 'create_time', 'update_time'], 'safe'],
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
        $query = TblPostPassage::find();

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
            'posten_passage_ID' => $this->posten_passage_ID,
            'post_ID' => $this->post_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'gepasseerd' => $this->gepasseerd,
            'binnenkomst' => $this->binnenkomst,
            'vertrek' => $this->vertrek,
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
//		$criteria->with=array('post', 'group', 'createUser');
//		$criteria->compare('posten_passage_ID',$this->posten_passage_ID);
//		$criteria->compare('post_ID',$this->post_ID);
//		$criteria->compare('t.event_ID',$event_id);
//		$criteria->compare('group_ID',$this->group_ID);
//		$criteria->compare('gepasseerd',$this->gepasseerd);
//		$criteria->compare('binnenkomst',$this->binnenkomst,true);
//		$criteria->compare('vertrek',$this->vertrek,true);
//		$criteria->compare('t.create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		$criteria->compare('group.group_name', $this->group_name,true);
//
//		$criteria->compare('post.post_name', $this->post_name,true);
//		$criteria->compare('post.date', $this->date,true);
//		$criteria->compare('post.score', $this->score,true);
//		$criteria->compare('createUser.username', $this->username,true);
//
//		$sort = new CSort();
//		$sort->attributes = array(
//			//'defaultOrder'=>'t.create_time ASC',
//			'group_name'=>array(
//				'asc'=>'group.group_name',
//				'desc'=>'group.group_name desc',
//			),
//			'post_name'=>array(
//				'asc'=>'post.post_name',
//				'desc'=>'post.post_name desc',
//			),
//			'date'=>array(
//				'asc'=>'post.date',
//				'desc'=>'post.date desc',
//			),
//			'binnenkomst'=>array(
//				'asc'=>'binnenkomst',
//				'desc'=>'binnenkomst desc',
//			),
//			'vertrek'=>array(
//				'asc'=>'vertrek',
//				'desc'=>'vertrek desc',
//			),
//			'score'=>array(
//				'asc'=>'post.score',
//				'desc'=>'post.score desc',
//			),
//			'username'=>array(
//				'asc'=>'createUser.username',
//				'desc'=>'createUser.username desc',
//			),
//			'create_time'=>array(
//				'asc'=>'t.create_time',
//				'desc'=>'t.create_time desc',
//			),
//		);
//
//	    return new CActiveDataProvider($this, array(
//		    'criteria'=>$criteria,
//			'sort'=>$sort
//	    ));
//	}

}
