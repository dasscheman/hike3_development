<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TimeTrail;

/**
 * TimeTrailSearch represents the model behind the search form about `app\models\TimeTrail`.
 */
class TimeTrailSearch extends TimeTrail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_ID', 'event_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['time_trail_name', 'create_time', 'update_time'], 'safe'],
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
        $query = TimeTrail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'time_trail_ID' => $this->time_trail_ID,
            'event_ID' => $this->event_ID,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'time_trail_name', $this->time_trail_name]);

        return $dataProvider;
    }
}
