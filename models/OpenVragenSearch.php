<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OpenVragen;

/**
 * OpenVragenSearch represents the model behind the search form about `app\models\OpenVragen`.
 */
class OpenVragenSearch extends OpenVragen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_vragen_ID', 'event_ID', 'route_ID', 'vraag_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['open_vragen_name', 'omschrijving', 'vraag', 'goede_antwoord', 'create_time', 'update_time'], 'safe'],
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
        $query = OpenVragen::find();

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
            'open_vragen_ID' => $this->open_vragen_ID,
            'event_ID' => $this->event_ID,
            'route_ID' => $this->route_ID,
            'vraag_volgorde' => $this->vraag_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'open_vragen_name', $this->open_vragen_name])
            ->andFilterWhere(['like', 'omschrijving', $this->omschrijving])
            ->andFilterWhere(['like', 'vraag', $this->vraag])
            ->andFilterWhere(['like', 'goede_antwoord', $this->goede_antwoord]);

        return $dataProvider;
    }

    public function searchQuestionNotAnsweredByGroup($params)
    {
        // Get group id of current user.
        $group_id = DeelnemersEvent::find()
            ->select('group_ID')
            ->where('event_ID =:event_id and user_ID =:user_id')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
            ->one();

        $event = EventNames::find()
            ->where('event_ID =:event_id')
            ->addParams([':event_id' => Yii::$app->user->identity->selected])
            ->one();

        $queryRoute = Route::find()
            ->select('route_ID')
            ->where('event_ID =:event_id and day_date =:day_date')
            ->params([':event_id' => Yii::$app->user->identity->selected, ':day_date' => $event->active_day]);

        // Find all answers for founr group id
        $queryAntwoorden = OpenVragenAntwoorden::find()
            ->select('open_vragen_ID')
            ->where('event_ID=:event_id AND group_ID=:group_id')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected,
                ':group_id' => $group_id->group_ID
            ]);

        // Find all questions NOT answered by found group id.
        $query = OpenVragen::find()
            ->where(['not in', 'tbl_open_vragen.open_vragen_ID', $queryAntwoorden])
            ->andWhere(['in', 'tbl_open_vragen.route_ID', $queryRoute])
            ->andWhere('event_ID=:event_id')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['vraag_volgorde'=>SORT_ASC]],
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
            'open_vragen_ID' => $this->open_vragen_ID,
            'event_ID' => $this->event_ID,
            'route_ID' => $this->route_ID,
            'vraag_volgorde' => $this->vraag_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'open_vragen_name', $this->open_vragen_name])
            ->andFilterWhere(['like', 'omschrijving', $this->omschrijving])
            ->andFilterWhere(['like', 'vraag', $this->vraag])
            ->andFilterWhere(['like', 'goede_antwoord', $this->goede_antwoord]);

        return $dataProvider;
    }
}
