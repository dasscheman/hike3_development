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
            [['user_ID', 'create_user_ID', 'update_user_ID', 'selected_event_ID'], 'integer'],
            [['username', 'voornaam', 'achternaam', 'organisatie', 'email', 
                'password', 'macadres', 'birthdate', 'last_login_time', 
                'create_time', 'update_time', 'authKey', 'accessToken'], 'safe'],
            [
                [
                    'user_ID', 'username', 'voornaam', 'achternaam', 'organisatie', 
                    'email', 'password', 'macadres', 'birthdate', 'last_login_time',
                    'create_time', 'create_user_ID', 'update_time', 'update_user_ID'
                ],
                'safe', 'on'=>'search'
            ],
            [
                [
                    'user_ID', 'username', 'voornaam', 'achternaam', 'organisatie',
                    'email', 'password', 'macadres', 'birthdate', 'last_login_time',
                    'create_time', 'create_user_ID', 'update_time', 'update_user_ID'
                ], 
                'safe', 'on'=>'searchPending'
            ],
            [
                [   
                    'user_ID', 'username', 'voornaam', 'achternaam', 'organisatie',
                    'email', 'password', 'macadres', 'birthdate', 'last_login_time',
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
        $query = Users::find();
        $queryFriendList = FriendList::find();
        $queryFriendList->select('friends_with_user_ID')
                        ->where('user_ID=:user_id')
                        ->addParams([':user_id' => Yii::$app->user->id]);
        $query->where(['not in', 'tbl_users.user_ID', $queryFriendList])
              ->andwhere('tbl_users.user_ID<>:user_id')
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
            'last_login_time' => $this->last_login_time,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'voornaam', $this->voornaam])
            ->andFilterWhere(['like', 'achternaam', $this->achternaam])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }

    public function searchFriends($params)
    {
        $query = Users::find();
        $query->join('inner join', 'tbl_friend_list', 'tbl_friend_list.user_ID = t.user_ID');
        $query->where(['not', ['friends.friend_list_ID' => null]])
              ->andWhere(['tbl_friend_list.friends_with_user_ID' => Yii::$app->user->id])
              ->andWhere(['tbl_friend_list.status' => 2]);
        
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
            'user_ID' => $this->user_ID,
            'birthdate' => $this->birthdate,
            'last_login_time' => $this->last_login_time,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
            'selected_event_ID' => $this->selected_event_ID,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'voornaam', $this->voornaam])
            ->andFilterWhere(['like', 'achternaam', $this->achternaam])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])           
            ->andFilterWhere(['like', 'macadres', $this->macadres])
            ->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);

        return $dataProvider;
    }

    public function searchPending($params)
    {
        $query = Users::find();
        $query->join('inner join', 'tbl_friend_list', 'tbl_friend_list.friends_with_user_ID = t.user_ID');
        $query->where(['not', ['friends.friend_list_ID' => null]])
              ->andWhere(['tbl_friend_list.user_ID' => Yii::$app->user->id])
              ->andWhere(['tbl_friend_list.status' => 0]);      
        
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
            'user_ID' => $this->user_ID,
            'birthdate' => $this->birthdate,
            'last_login_time' => $this->last_login_time,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
            'selected_event_ID' => $this->selected_event_ID,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'voornaam', $this->voornaam])
            ->andFilterWhere(['like', 'achternaam', $this->achternaam])
            ->andFilterWhere(['like', 'organisatie', $this->organisatie])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'password', $this->password])           
            ->andFilterWhere(['like', 'macadres', $this->macadres])
            ->andFilterWhere(['like', 'authKey', $this->authKey])
            ->andFilterWhere(['like', 'accessToken', $this->accessToken]);

        return $dataProvider;
    }
}