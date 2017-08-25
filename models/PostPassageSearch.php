<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PostPassage;

/**
 * PostPassageSearch represents the model behind the search form about `app\models\PostPassage`.
 */
class PostPassageSearch extends PostPassage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['posten_passage_ID', 'post_ID', 'event_ID', 'group_ID', 'gepasseerd', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['binnenkomst', 'vertrek', 'create_time', 'update_time'], 'safe'],
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
        $query = PostPassage::find();

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
            'posten_passage_ID' => $this->posten_passage_ID,
            'post_ID' => $this->post_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'gepasseerd' => $this->gepasseerd,
            'binnenkomst' => $this->binnenkomst,
            'vertrek' => $this->vertrek,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
}
