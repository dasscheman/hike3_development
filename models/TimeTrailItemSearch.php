<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TimeTrailItem;

/**
 * TimeTrailItemSearch represents the model behind the search form about `app\models\TimeTrailItem`.
 */
class TimeTrailItemSearch extends TimeTrailItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_item_ID', 'time_trail_ID', 'event_ID', 'volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['time_trail_item_name', 'code', 'max_time', 'create_time', 'update_time'], 'safe'],
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
       //d($params);
        $query = TimeTrailItem::find()
            ->where('event_ID =:event_id', array(':event_id' => Yii::$app->user->identity->selected_event_ID))
            ->orderBy('volgorde ASC');


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
            'time_trail_item_ID' => $this->time_trail_item_ID,
            'time_trail_ID' => $this->time_trail_ID,
            'event_ID' => $this->event_ID,
            'volgorde' => $this->volgorde,
            'score' => $this->score,
            'max_time' => $this->max_time,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'time_trail_item_name', $this->time_trail_item_name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
