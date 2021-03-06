<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form about `app\models\Users`.
 */
class UsersSearch extends Users
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_user_ID', 'update_user_ID', 'selected_event_ID'], 'integer'],
            [['username', 'voornaam', 'achternaam', 'organisatie', 'email',
                'password', 'macadres', 'birthdate', 'last_login_at',
                'create_time', 'update_time', 'authKey', 'accessToken', 'search_friends'], 'safe'],
            [
                [
                    'id', 'username', 'voornaam', 'achternaam', 'organisatie',
                    'email', 'password', 'macadres', 'birthdate', 'last_login_at',
                    'create_time', 'create_user_ID', 'update_time', 'update_user_ID'
                ],
                'safe', 'on'=>'search'
            ],
            [
                [
                    'id', 'username', 'voornaam', 'achternaam', 'organisatie',
                    'email', 'password', 'macadres', 'birthdate', 'last_login_at',
                    'create_time', 'create_user_ID', 'update_time', 'update_user_ID'
                ],
                'safe', 'on'=>'searchPending'
            ],
            [
                [
                    'id', 'username', 'voornaam', 'achternaam', 'organisatie',
                    'email', 'password', 'macadres', 'birthdate', 'last_login_at',
                    'create_time', 'create_user_ID', 'update_time', 'update_user_ID'
                ],
                'safe', 'on'=>'searchFriends'
            ],
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
    public function searchNewFriends($params)
    {
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
            ->where('user_ID=:user_id')
            ->addParams([':user_id' => Yii::$app->user->id]);

        $query = Users::find();
        $query->where(['not in', 'user.id', $queryFriendList])
            ->andwhere('user.id<>:user_id')
            ->andWhere('ISNULL(blocked_at)')
            ->addParams([':user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);

        if (!isset($this->search_friends) ||
            strlen($this->search_friends) < 3) {
            $query->where('0=1');
        }
        // dd($this);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(
            ['or',
                ['like','voornaam',$this->search_friends],
                ['like','achternaam',$this->search_friends],
                ['like','organisatie',$this->search_friends],
                ['like','email',$this->search_friends]
            ]
        );

        return $dataProvider;
    }

    public function searchFriends($params)
    {
        $query = Users::find();
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_accepted])
                        ->addParams([':user_id' => Yii::$app->user->id]);
        $query->where(['in', 'user.id', $queryFriendList])
              ->andWhere('ISNULL(blocked_at)')
              ->addParams([':user_id' => Yii::$app->user->id]);

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
            'birthdate' => $this->birthdate,
            'last_login_at' => $this->last_login_at,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'voornaam', $this->voornaam])
            ->andFilterWhere(['like', 'achternaam', $this->achternaam])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    public function searchFriendRequests($params)
    {
        $query = Users::find();
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->addParams([':user_id' => Yii::$app->user->id])
                        ->andWhere(['tbl_friend_list.status' => FriendList::STATUS_pending]);
        $query->where(['in', 'user.id', $queryFriendList])
              ->andWhere('ISNULL(blocked_at)')
              ->andwhere('usser.id<>:user_id')
              ->addParams([':user_id' => Yii::$app->user->id]);

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
            'birthdate' => $this->birthdate,
            'last_login_at' => $this->last_login_at,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'voornaam', $this->voornaam])
            ->andFilterWhere(['like', 'achternaam', $this->achternaam])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
