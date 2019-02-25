<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Bonuspunten;

/**
 * BonuspuntenSearch represents the model behind the search form about `app\models\Bonuspunten`.
 */
class BonuspuntenSearch extends Bonuspunten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bouspunten_ID', 'event_ID', 'post_ID', 'group_ID', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['date', 'omschrijving', 'create_time', 'update_time'], 'safe'],
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
        $query = Bonuspunten::find();
        $query->joinWith(['group', 'createUser']);
        $query->where(
            'tbl_bonuspunten.event_ID = :event_id',
            [':event_id'=>Yii::$app->user->identity->selected_event_ID]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
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
            'bouspunten_ID' => $this->bouspunten_ID,
            'date' => $this->date,
            'post_ID' => $this->post_ID,
            'group_ID' => $this->group_ID,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);

        return $dataProvider;
    }

    public function searchByGroup($params, $group_id = NULL)
    {
        if ($group_id === NULL) {
            // Get group id of current user.
            $groupModel = DeelnemersEvent::find()
                ->select('group_ID')
                ->where('tbl_deelnemers_event.event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected_event_ID, ':user_id' => Yii::$app->user->id])
                ->one();
            $group_id = $groupModel->group_ID;
        }
        // Find all answers for founr group id
        $query = Bonuspunten::find()
            // ->select('open_vragen_ID')
            ->where('tbl_bonuspunten.event_ID=:event_id AND group_ID=:group_id')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected_event_ID,
                ':group_id' => $group_id
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_DESC]],
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
            'bouspunten_ID' => $this->bouspunten_ID,
            'event_ID' => $this->event_ID,
            'date' => $this->date,
            'post_ID' => $this->post_ID,
            'group_ID' => $this->group_ID,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'omschrijving', $this->omschrijving]);

        return $dataProvider;
    }
}
