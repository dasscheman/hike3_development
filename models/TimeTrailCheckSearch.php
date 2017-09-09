<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TimeTrailCheck;

/**
 * TimeTrailCheckSearch represents the model behind the search form about `app\models\TimeTrailCheck`.
 */
class TimeTrailCheckSearch extends TimeTrailCheck
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['time_trail_check_ID', 'time_trail_item_ID', 'event_ID', 'group_ID', 'succeded', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['start_time', 'end_time', 'create_time', 'update_time'], 'safe'],
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
    public function search($params, $group_id = NULL)
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


        $query = TimeTrailCheck::find()
                ->where('event_ID =:event_id AND group_ID =:group_id')
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':group_id' => $group_id
                ])
                ->orderBy(['create_time' => SORT_DESC]);

        // add conditions that should always apply here
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

        // grid filtering conditions
        $query->andFilterWhere([
            'time_trail_check_ID' => $this->time_trail_check_ID,
            'time_trail_item_ID' => $this->time_trail_item_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'succeded' => $this->succeded,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchLastItem($params, $group_id = NULL)
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


        $query = TimeTrailCheck::find()
                ->where('event_ID =:event_id AND group_ID =:group_id')
                ->andWhere(['is', 'end_time', null])
                ->params([
                    ':event_id' => Yii::$app->user->identity->selected_event_ID,
                    ':group_id' => $group_id
                ]);

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
            'time_trail_check_ID' => $this->time_trail_check_ID,
            'time_trail_item_ID' => $this->time_trail_item_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'succeded' => $this->succeded,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
}
