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
	public $trail_score;
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
        $event_id = Yii::$app->user->identity->selected_event_ID;
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

        $TrailQuery = TimeTrailCheck::find()
            ->select([
                'tbl_time_trail_check.group_ID as trail_group_ID',
                'tbl_time_trail_check.succeded as succeded',
                'IFNULL(SUM(tbl_time_trail_item.score), 0) as trail_score'
            ])
            ->where('succeded=:succeded')
            ->innerJoinWith('timeTrailItem', false)
            ->addParams([':succeded' => TRUE])
            ->groupBy('tbl_time_trail_check.group_ID');

        $VraagQuery = OpenVragenAntwoorden::find()
            ->select([
                'tbl_open_vragen_antwoorden.group_ID as vraag_group_ID',
                'tbl_open_vragen_antwoorden.correct as correct',
                'tbl_open_vragen_antwoorden.checked as checked',
                'IFNULL(SUM(tbl_open_vragen.score), 0) as vragen_score'
            ])
            ->where('correct=:correct')
            ->andWhere('checked=:checked')
            ->innerJoinWith('openVragen', TRUE)
            ->addParams([':correct' => TRUE, ':checked' => TRUE])
            ->groupBy('tbl_open_vragen_antwoorden.group_ID');

        $query->leftJoin(['orderBonusSum' => $bonusQuery], 'orderBonusSum.bonus_group_ID = group_ID');
        $query->leftJoin(['orderOpenHintSum' => $openHintQuery], 'orderOpenHintSum.hint_group_ID = group_ID');
        $query->leftJoin(['orderPassedPostsSum' => $passedPostQuery], 'orderPassedPostsSum.post_group_ID = group_ID');
        $query->leftJoin(['orderQrSum' => $QrQuery], 'orderQrSum.qr_group_ID = group_ID');
        $query->leftJoin(['orderTrailSum' => $TrailQuery], 'orderTrailSum.trail_group_ID = group_ID');
        $query->leftJoin(['orderVraagSum' => $VraagQuery], 'orderVraagSum.vraag_group_ID = group_ID');

        $query->select([
            '*',
            '(COALESCE(bonus_score, 0) + COALESCE(post_score, 0) + COALESCE(qr_score, 0) + COALESCE(vragen_score, 0) + COALESCE(trail_score, 0) - COALESCE(hint_score, 0)) as total_score'
        ]);
        $query->groupBy('tbl_groups.group_ID');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['rank'=>SORT_DESC]]
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
                 'trail_score' => [
                     'asc' => ['trail_score' => SORT_ASC],
                     'desc' => ['trail_score' => SORT_DESC],
                     'label' => 'Order Trail Score'
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
}