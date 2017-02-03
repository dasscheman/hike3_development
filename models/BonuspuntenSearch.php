<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bonuspunten;

/**
 * BonuspuntenSearch represents the model behind the search form about `app\models\Bonuspunten`.
 */
class BonuspuntenSearch extends Bonuspunten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bouspunten_ID', 'event_ID', 'post_ID', 'group_ID', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['date', 'omschrijving', 'create_time', 'update_time'], 'safe'],
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
        $query = Bonuspunten::find();
        $query->joinWith(['group', 'createUser']);
        $query->where(
            'tbl_bonuspunten.event_ID = :event_id',
            [':event_id'=>Yii::$app->user->identity->selected]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
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
            'bouspunten_ID' => $this->bouspunten_ID,
            'event_ID' => $this->event_ID,
            'date' => $this->date,
            'post_ID' => $this->post_ID,
            'group_ID' => $this->group_ID,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);

        return $dataProvider;
    }


//	/**
//	 * Retrieves a list of models based on the current search/filter conditions.
//	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
//	 */
//	public function search($event_id)
//	{
//		// Warning: Please modify the following code to remove attributes that
//		// should not be searched.
//
//		$criteria=new CDbCriteria;
//
//		$criteria->with=array('post', 'group', 'createUser');
//		$criteria->select = array(
//			't.event_ID',
//			'group_ID',
//			'date',
//			'omschrijving',
//			'score',
//			'create_time',
//			'post.post_name AS post_name');
//		$criteria->compare('bouspunten_ID',$this->bouspunten_ID);
//		$criteria->compare('t.event_ID',$event_id);
//		$criteria->compare('t.date',$this->date);
//		$criteria->compare('post.post_name',$this->post_name);
//		$criteria->compare('group_ID',$this->group_ID);
//		$criteria->compare('omschrijving',$this->omschrijving,true);
//		$criteria->compare('t.score',$this->score);
//		$criteria->compare('t.create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		$criteria->compare('group.group_name', $this->group_name,true);
//		$criteria->compare('post.post_name', $this->post_name,true);
//		$criteria->compare('createUser.username', $this->username,true);
//
//		$sort = new CSort();
//		$sort->attributes = array(
//			//'defaultOrder'=>'t.create_time ASC',
//			'group_name'=>array(
//				'asc'=>'group.group_name',
//				'desc'=>'group.group_name desc',
//			),
//			'date'=>array(
//				'asc'=>'t.date',
//				'desc'=>'t.date desc',
//			),
//			'post_name'=>array(
//				'asc'=>'post_name',
//				'desc'=>'post_name desc',
//			),
//			'omschrijving'=>array(
//				'asc'=>'omschrijving',
//				'desc'=>'omschrijving desc',
//			),
//			'score'=>array(
//				'asc'=>'t.score',
//				'desc'=>'t.score desc',
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
