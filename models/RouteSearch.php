<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Route;

/**
 * RouteSearch represents the model behind the search form about `app\models\Route`.
 */
class RouteSearch extends Route
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_ID', 'event_ID', 'route_volgorde', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['route_name', 'day_date', 'create_time', 'update_time'], 'safe'],
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
        $query = Route::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['route_volgorde'=>SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'route_ID' => $this->route_ID,
            'event_ID' => $this->event_ID,
            'day_date' => $this->day_date,
            'route_volgorde' => $this->route_volgorde,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'route_name', $this->route_name]);

        return $dataProvider;
    }

    public function searchRouteInEvent($params)
    {
        $event = EventNames::findOne([
            'event_ID' => Yii::$app->user->identity->selected_event_ID]);
        $query = Route::find()
            ->where('event_ID =:event_id AND (ISNULL(day_date) OR (day_date >=:start_date AND day_date <=:end_date))')
            ->params([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':start_date' => $event->start_date, 
                ':end_date' => $event->end_date])
            ->orderBy('route_volgorde ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'  => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
           return $dataProvider;
        }

        $query->andFilterWhere([
            'route_ID' => $this->route_ID,
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'day_date' => $this->day_date,
            'route_volgorde' => $this->route_volgorde,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }

    public function searchIntroRouteInEvent()
    {
        $query = Route::find()
            ->where('event_ID =:event_id AND (ISNULL(day_date) OR day_date =:day_date)')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':day_date' => '0000-00-00'
            ])
            ->orderBy('route_volgorde ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'  => false,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
           return $dataProvider;
        }

        $query->andFilterWhere([
            'route_ID' => $this->route_ID,
            'event_ID' => Yii::$app->user->identity->selected_event_ID,
            'day_date' => $this->day_date,
            'route_volgorde' => $this->route_volgorde,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
}
