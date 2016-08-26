<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblGroups;

/**
 * TblGroupsSearch represents the model behind the search form about `app\models\TblGroups`.
 */
class TblGroupsSearch extends TblGroups
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_ID', 'event_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['group_name', 'create_time', 'update_time'], 'safe'],
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
        $event_Id = $_GET['event_id'];
        $query = Groups::find()->where = "event_ID = $event_Id";

        $dataProvider = new ActiveDataProvider([
            'query' => $query, 
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'title' => SORT_ASC, 
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'group_ID' => $this->group_ID,
            'event_ID' => $this->event_ID,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'group_name', $this->group_name]);

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
//		$criteria->compare('group_ID',$this->group_ID);
//		$criteria->compare('event_ID',$this->event_ID);
//		$criteria->compare('group_name',$this->group_name,true);
//		$criteria->compare('create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));
//	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchScore($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
			
		$criteria->with=array(
			'deelnemersEvents'=>array('together'=>true, 'joinType'=>'LEFT JOIN'), 
			'deelnemersEvents.user'=>array('select'=>'username', 'together'=>false, 'joinType'=>'LEFT JOIN'), 
		);

		$criteria->join =
			'LEFT JOIN tbl_hint_score ON tbl_hint_score.group_ID = t.group_ID
			LEFT JOIN tbl_qr_score ON tbl_qr_score.group_ID = t.group_ID
			LEFT JOIN tbl_posten_score ON tbl_posten_score.group_ID = t.group_ID
			LEFT JOIN tbl_vragen_score ON tbl_vragen_score.group_ID = t.group_ID
			LEFT JOIN tbl_bonus_score ON tbl_bonus_score.group_ID = t.group_ID
			LEFT JOIN tbl_totaal_score ON tbl_totaal_score.group_ID = t.group_ID';

		$criteria->select = array(
			'event_ID',
			'group_ID',
			'group_concat(DISTINCT user.username SEPARATOR " ") AS group_members',
			'tbl_bonus_score.bonus_score AS bonus_score',
			'tbl_hint_score.hint_score AS hint_score',
			'tbl_vragen_score.vragen_score AS vragen_score',
			'tbl_posten_score.post_score AS post_score',
			'tbl_qr_score.qr_score AS qr_score',
			'tbl_totaal_score.totaal_score AS totaal_score',
			'group_name');

		$criteria->group = 't.group_ID';
		$criteria->compare('t.event_ID',$event_id);
		$criteria->compare('group_name',$this->group_name, true);
		$criteria->compare('user.username',$this->group_members, true);
		$criteria->compare('bonus_score',$this->bonus_score, true);
		$criteria->compare('posten_score',$this->post_score, true);
		$criteria->compare('qr_score',$this->qr_score, true);
		$criteria->compare('vragen_score',$this->vragen_score, true);
		$criteria->compare('hint_score',$this->hint_score, true);
		$criteria->compare('totaal_score',$this->totaal_score, true);
		//$criteria->compare('rank',$this->totaal_score,true);

		$sort = new CSort();
		$sort->attributes = array(
			'group_name'=>array(
				'asc'=>'group_name',
				'desc'=>'group_name desc',
			),
			'group_members'=>array(
				'asc'=>'group_members',
				'desc'=>'group_members desc',
			),
			'bonus_score'=>array(
				'asc'=>'bonus_score',
				'desc'=>'bonus_score desc',
			),
			'post_score'=>array(
				'asc'=>'post_score',
				'desc'=>'post_score desc',
			),
			'qr_score'=>array(
				'asc'=>'qr_score',
				'desc'=>'qr_score desc',
			),
			'vragen_score'=>array(
				'asc'=>'vragen_score',
				'desc'=>'vragen_score desc',
			),
			'hint_score'=>array(
				'asc'=>'hint_score',
				'desc'=>'hint_score desc',
			),
			'totaal_score'=>array(
				'asc'=>'totaal_score',
				'desc'=>'totaal_score desc',
			),
		);

		$sort->defaultOrder = array('totaal_score'=>true);
	    return new CActiveDataProvider($this, array(
		    'criteria'=>$criteria,
			'pagination'=>array(
				 'pageSize'=>10
				 ),
			'sort'=>$sort
	    ));
	}

	public function searchPost($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria=new CDbCriteria;
			
		$criteria->with=array(
			'deelnemersEvents'=>array('together'=>true, 'joinType'=>'LEFT JOIN'), 
			'deelnemersEvents.user'=>array('select'=>'username', 'together'=>false, 'joinType'=>'LEFT JOIN'), 
			'postPassages'=>array('together'=>true, 'joinType'=>'LEFT JOIN'), 
			'postPassages.post'=>array('together'=>true, 'joinType'=>'LEFT JOIN'), 
		);

		$criteria->select = array(
			'group_name',
			'group_ID',
			'event_ID',
			'group_concat(DISTINCT user.username SEPARATOR " ") AS group_members',
			'postPassages.binnenkomst AS last_post_time',
			'post.post_name AS last_post');  
		$criteria->order = 'last_post_time DESC';
		$criteria->group = 't.group_ID, postPassages.group_ID';
		$criteria->compare('group_ID',$this->group_ID);
		$criteria->compare('t.event_ID',$event_id);
		$criteria->compare('group_name',$this->group_name,true);
		$criteria->compare('user.username',$this->group_members,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		$sort = new CSort();
		$sort->attributes = array(
			//'defaultOrder'=>'t.create_time ASC',
			'group_name'=>array(
				'asc'=>'group_name',
				'desc'=>'group_name desc',
			),
			'group_members'=>array(
				'asc'=>'group_members',
				'desc'=>'group_members',
			),
		);

		$sort->defaultOrder = array('group_name'=>true);
	    return new CActiveDataProvider($this, array(
		    'criteria'=>$criteria,
			'pagination'=>array(
				 'pageSize'=>10
				 ),
			'sort'=>$sort
	    ));
	}

}
