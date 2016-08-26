<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FriendList;

/**
 * FriendListSearch represents the model behind the search form about `app\models\FriendList`.
 */
class FriendListSearch extends FriendList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['friend_list_ID', 'user_ID', 'friends_with_user_ID', 'status', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
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
        $query = FriendList::find();

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
            'friend_list_ID' => $this->friend_list_ID,
            'user_ID' => $this->user_ID,
            'friends_with_user_ID' => $this->friends_with_user_ID,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
}
