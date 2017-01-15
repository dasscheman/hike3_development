<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Groups;

/**
 * GroupsSearch represents the model behind the search form about `app\models\Groups`.
 */
class GroupsSearch extends Groups
{
    public $bonus_score;
    public $hint_score;
    public $post_score;
	public $qr_score;
	public $vragen_score;
	public $total_score;
	public $rank;
	public $time_walking;
	public $time_left;
	public $last_post;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_ID', 'event_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [[
                'group_name', 'create_time', 'update_time',
                'bonus_score', 'hint_score', 'post_score',
                'vragen_score', 'qr_score', 'total_score',
                'rank', 'time_walking', 'time_left', 'last_post'], 'safe'],
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
        $event_id = Yii::$app->user->identity->selected;
        $query = Groups::find()
            ->where("event_ID =:event_id")
            ->addParams([':event_id' => $event_id]);

        $bonusQuery = Bonuspunten::find()
            ->select([
                'group_ID as bonus_group_ID',
                'IFNULL(SUM(score), 0) as bonus_score'
            ])
            ->groupBy('bonus_group_ID');

        $openHintQuery = OpenNoodEnvelop::find()
            ->select([
                'tbl_open_nood_envelop.group_ID as hint_group_ID',                
                'IFNULL(SUM(tbl_nood_envelop.score), 0) as hint_score'
            ])
            ->innerJoinWith('noodEnvelop', false)
            ->groupBy('tbl_open_nood_envelop.group_ID');

        $passedPostQuery = PostPassage::find()
            ->select([
                'tbl_post_passage.group_ID as post_group_ID',
                'IFNULL(SUM(tbl_posten.score), 0) as post_score'
            ])
            ->innerJoinWith('post', false)
            ->groupBy('tbl_post_passage.group_ID');

        $QrQuery = QrCheck::find()
            ->select([
                'tbl_qr_check.group_ID as qr_group_ID',
                'IFNULL(SUM(tbl_qr.score), 0) as qr_score'
            ])
            ->innerJoinWith('qr', false)
            ->groupBy('tbl_qr_check.group_ID');

        $VraagQuery = OpenVragenAntwoorden::find()
            ->select([
                'tbl_open_vragen_antwoorden.group_ID as vraag_group_ID',
                'IFNULL(SUM(tbl_open_vragen.score), 0) as vragen_score'
            ])
            ->innerJoinWith('openVragen', false)
            ->groupBy('tbl_open_vragen_antwoorden.group_ID');


        $query->leftJoin(['orderBonusSum' => $bonusQuery], 'orderBonusSum.bonus_group_ID = group_ID');
        $query->leftJoin(['orderOpenHintSum' => $openHintQuery], 'orderOpenHintSum.hint_group_ID = group_ID');
        $query->leftJoin(['orderPassedPostsSum' => $passedPostQuery], 'orderPassedPostsSum.post_group_ID = group_ID');
        $query->leftJoin(['orderQrSum' => $QrQuery], 'orderQrSum.qr_group_ID = group_ID');
        $query->leftJoin(['orderVraagSum' => $VraagQuery], 'orderVraagSum.vraag_group_ID = group_ID');

        $query->select([ 
            '*',
            '(COALESCE(bonus_score, 0) + COALESCE(post_score, 0) + COALESCE(qr_score, 0) + COALESCE(vragen_score, 0) - COALESCE(hint_score, 0)) as total_score'
        ]);
        $query->groupBy('tbl_groups.group_ID');

        $dataProvider = new ActiveDataProvider([
            'query' => $query, 
        ]);

        /**
          * Setup your sorting attributes
          * Note: This is setup before the $this->load($params) 
          * statement below
          */
          $dataProvider->setSort([
             'attributes' => [
                 'group_name',
                 'bonus_score' => [
                     'asc' => ['bonus_score' => SORT_ASC],
                     'desc' => ['bonus_score' => SORT_DESC],
                     'label' => 'Order Bonus Score'
                 ],
                 'hint_score' => [
                     'asc' => ['hint_score' => SORT_ASC],
                     'desc' => ['hint_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ],
                 'post_score' => [
                     'asc' => ['post_score' => SORT_ASC],
                     'desc' => ['post_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ],
                 'qr_score' => [
                     'asc' => ['qr_score' => SORT_ASC],
                     'desc' => ['qr_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ],
                 'vragen_score' => [
                     'asc' => ['vragen_score' => SORT_ASC],
                     'desc' => ['vragen_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ],
                 'total_score' => [
                     'asc' => ['total_score' => SORT_ASC],
                     'desc' => ['total_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ],
                 'rank' => [
                     'asc' => ['total_score' => SORT_ASC],
                     'desc' => ['total_score' => SORT_DESC],
                     'label' => 'Order Hint Score'
                 ]
             ]
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

        $query->andFilterWhere(['LIKE', 'group_name', $this->group_name]);

        return $dataProvider;
    }

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
