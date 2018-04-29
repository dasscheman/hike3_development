<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NewsletterMailList;

/**
 * NewsletterMailListSearch represents the model behind the search form of `app\models\NewsletterMailList`.
 */
class NewsletterMailListSearch extends NewsletterMailList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'newsletter_id', 'user_id', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['email', 'send_time', 'is_sent', 'create_time', 'update_time'], 'safe'],
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
        $query = NewsletterMailList::find();

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
            'id' => $this->id,
            'newsletter_id' => $this->newsletter_id,
            'user_id' => $this->user_id,
            'create_time' => $this->create_time,
            'send_time' => $this->send_time,
            'is_sent' => $this->is_sent,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
