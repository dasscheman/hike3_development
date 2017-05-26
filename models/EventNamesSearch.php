<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EventNames;

/**
 * EventNamesSearch represents the model behind the search form about `app\models\EventNames`.
 */
class EventNamesSearch extends EventNames
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_ID', 'status', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['event_name', 'start_date', 'end_date', 'active_day', 'max_time', 'image', 'organisatie', 'website', 'create_time', 'update_time'], 'safe'],
            [['event_ID', 'event_name', 'start_date', 'end_date', 'status',
                'active_day', 'create_time', 'create_user_ID', 'update_time',
                'update_user_ID'], 'safe', 'on'=>'search'],
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
        $query = EventNames::find();
        $query->join(
            'INNER JOIN',
            'tbl_deelnemers_event',
            'tbl_deelnemers_event.event_ID = t.event_ID');
        $query->onCondition(
            'deelnemers.user_ID = :currentuser',
            [':currentuser'=>Yii::app()->user->id]);
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
            'event_ID' => $this->event_ID,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'active_day' => $this->active_day,
            'max_time' => $this->max_time,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'event_name', $this->event_name])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'website', $this->website]);

        return $dataProvider;
    }
}
