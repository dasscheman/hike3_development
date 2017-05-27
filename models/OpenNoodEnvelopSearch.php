<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OpenNoodEnvelop;

/**
 * OpenNoodEnvelopSearch represents the model behind the search form about `app\models\OpenNoodEnvelop`.
 */
class OpenNoodEnvelopSearch extends OpenNoodEnvelop
{
    public $nood_envelop_name;
  	public $group_name;
  	public $route_name;
  	public $username;
  	public $score;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_nood_envelop_ID', 'nood_envelop_ID', 'event_ID', 'group_ID', 'opened', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time', 'group_name', 'nood_envelop_name', 'score', 'username', 'route_name'], 'safe'],
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
        $query = OpenNoodEnvelop::find();
        $query->joinWith(['noodEnvelop', 'group', 'createUser' , 'noodEnvelop.route']);
        $query->where(
            'tbl_open_nood_envelop.event_ID = :event_id',
            [':event_id'=>Yii::$app->user->identity->selected_event_ID]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $dataProvider->sort->attributes['group_name'] =
        [
            'asc' => ['tbl_groups.group_name' => SORT_ASC],
            'desc' => ['tbl_groups.group_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['nood_envelop_name'] =
        [
            'asc' => ['tbl_nood_envelop.nood_envelop_name' => SORT_ASC],
            'desc' => ['tbl_nood_envelop.nood_envelop_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['score'] =
        [
            'asc' => ['tbl_nood_envelop.score' => SORT_ASC],
            'desc' => ['tbl_nood_envelop.score' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['username'] =
        [
            'asc' => ['tbl_users.username' => SORT_ASC],
            'desc' => ['tbl_users.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['route_name'] =
        [
            'asc' => ['tbl_route.route_name' => SORT_ASC],
            'desc' => ['tbl_route.route_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'open_nood_envelop_ID' => $this->open_nood_envelop_ID,
            'nood_envelop_ID' => $this->nood_envelop_ID,
            'group_ID' => $this->group_ID,
            'opened' => $this->opened,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ])
            ->andFilterWhere(['like', 'tbl_groups.group_name', $this->group_name])
            ->andFilterWhere(['like', 'tbl_nood_envelop.nood_envelop_name', $this->nood_envelop_name])
            ->andFilterWhere(['like', 'tbl_nood_envelop.score', $this->score])
            ->andFilterWhere(['like', 'tbl_users.username', $this->username])
            ->andFilterWhere(['like', 'tbl_route.route_name', $this->route_name]);

        return $dataProvider;
    }
}
