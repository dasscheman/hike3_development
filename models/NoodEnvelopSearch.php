<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblNoodEnvelop;

/**
 * TblNoodEnvelopSearch represents the model behind the search form about `app\models\TblNoodEnvelop`.
 */
class TblNoodEnvelopSearch extends TblNoodEnvelop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nood_envelop_ID', 'event_ID', 'route_ID', 'nood_envelop_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['nood_envelop_name', 'coordinaat', 'opmerkingen', 'create_time', 'update_time'], 'safe'],
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
        $query = TblNoodEnvelop::find();

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
            'nood_envelop_ID' => $this->nood_envelop_ID,
            'event_ID' => $this->event_ID,
            'route_ID' => $this->route_ID,
            'nood_envelop_volgorde' => $this->nood_envelop_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'nood_envelop_name', $this->nood_envelop_name])
            ->andFilterWhere(['like', 'coordinaat', $this->coordinaat])
            ->andFilterWhere(['like', 'opmerkingen', $this->opmerkingen]);

        return $dataProvider;
    }
}
