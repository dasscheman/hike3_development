<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OpenVragenAntwoorden;

/**
 * TblOpenVragenAntwoordenSearch represents the model behind the search form about `app\models\OpenVragenAntwoorden`.
 */
class OpenVragenAntwoordenSearch extends OpenVragenAntwoorden
{
    public $open_vragen_name;
    public $vraag;
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
            [[
                'open_vragen_antwoorden_ID', 'open_vragen_ID', 'event_ID', 'group_ID',
                'checked', 'correct', 'create_user_ID', 'update_user_ID'], 'integer'],
            [[
                'antwoord_spelers', 'create_time', 'update_time', 'group_name',
                'open_vragen_name', 'score', 'username', 'route_name', 'vraag'], 'safe'],
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
        $query = OpenVragenAntwoorden::find();
        $query->joinWith(['openVragen', 'group', 'createUser' , 'openVragen.route']);
        $query->where(
            'tbl_open_vragen_antwoorden.event_ID = :event_id',
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
        $dataProvider->sort->attributes['open_vragen_name'] =
        [
            'asc' => ['tbl_open_vragen.open_vragen_name' => SORT_ASC],
            'desc' => ['tbl_open_vragen.open_vragen_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['score'] =
        [
            'asc' => ['tbl_open_vragen.score' => SORT_ASC],
            'desc' => ['tbl_open_vragen.score' => SORT_DESC],
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
        $dataProvider->sort->attributes['vraag'] =
        [
            'asc' => ['openVragen.vraag' => SORT_ASC],
            'desc' => ['openVragen.vraag' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['antwoord_spelers'] =
        [
            'asc' => ['antwoord_spelers' => SORT_ASC],
            'desc' => ['antwoord_spelers' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['goede_antwoord'] =
        [
            'asc' => ['openVragen.goede_antwoord' => SORT_ASC],
            'desc' => ['openVragen.goede_antwoord' => SORT_DESC],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'open_vragen_antwoorden_ID' => $this->open_vragen_antwoorden_ID,
            'open_vragen_ID' => $this->open_vragen_ID,
            'group_ID' => $this->group_ID,
            'checked' => $this->checked,
            'correct' => $this->correct,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ])
            ->andFilterWhere(['like', 'antwoord_spelers', $this->antwoord_spelers])
            ->andFilterWhere(['like', 'tbl_groups.group_name', $this->group_name])
            ->andFilterWhere(['like', 'tbl_opn_vragen.open_vragen_name', $this->open_vragen_name])
            ->andFilterWhere(['like', 'tbl_open_vragen.score', $this->score])
            ->andFilterWhere(['like', 'tbl_users.username', $this->username])
            ->andFilterWhere(['like', 'tbl_route.route_name', $this->route_name]);

        return $dataProvider;
    }

    public function searchQuestionAnsweredByGroup($params, $group_id = NULL)
    {
        if ($group_id === NULL) {
            // Get group id of current user.
            $groupModel = DeelnemersEvent::find()
                ->select('group_ID')
                ->where('event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
                ->one();
            $group_id = $groupModel->group_ID;
        }
        // Find all answers for founr group id
        $query = OpenVragenAntwoorden::find()
            ->where('event_ID=:event_id AND group_ID=:group_id AND checked=:checked')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':group_id' => $group_id,
                ':checked' => TRUE
            ])
            ->orderBy('update_time DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'open_vragen_antwoorden_ID' => $this->open_vragen_antwoorden_ID,
            'open_vragen_ID' => $this->open_vragen_ID,
            'group_ID' => $this->group_ID,
            'checked' => $this->checked,
            'correct' => $this->correct,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'antwoord_spelers', $this->antwoord_spelers]);

        return $dataProvider;
    }
}
