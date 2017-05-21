<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\QrCheck;

/**
 * QrCheckSearch represents the model behind the search form about `app\models\QrCheck`.
 */
class QrCheckSearch extends QrCheck
{
    public $qr_name;
    public $group_name;
    public $route_name;
    public $username;
    public $score;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qr_check_ID', 'qr_ID', 'event_ID', 'group_ID', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['create_time', 'update_time', 'group_name', 'qr_name', 'score', 'username', 'route_name'], 'safe'],
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
        $query = QrCheck::find();
        $query->joinWith(['qr', 'group', 'createUser' , 'qr.route']);
        $query->where(
            'tbl_qr_check.event_ID = :event_id',
            [':event_id'=>Yii::$app->user->identity->selected]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['create_time'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->sort->attributes['group_name'] =
        [
            'asc' => ['tbl_groups.group_name' => SORT_ASC],
            'desc' => ['tbl_groups.group_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['qr_name'] =
        [
            'asc' => ['tbl_qr.qr_name' => SORT_ASC],
            'desc' => ['tbl_qr.qr_name' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['score'] =
        [
            'asc' => ['tbl_qr.score' => SORT_ASC],
            'desc' => ['tbl_qr.score' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['username'] =
        [
            'asc' => ['tbl_users.username' => SORT_ASC],
            'desc' => ['tbl_users.username' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['route_name'] =
        [
            'asc' => ['tbl_route.route_name' => SORT_ASC],
            'desc' => ['tbl_route.route_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'qr_check_ID' => $this->qr_check_ID,
            'qr_ID' => $this->qr_ID,
            'group_ID' => $this->group_ID,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ])
            ->andFilterWhere(['like', 'tbl_groups.group_name', $this->group_name])
            ->andFilterWhere(['like', 'tbl_qr.qr_name', $this->qr_name])
            ->andFilterWhere(['like', 'tbl_qr.score', $this->score])
            ->andFilterWhere(['like', 'tbl_users.username', $this->username])
            ->andFilterWhere(['like', 'tbl_route.route_name', $this->route_name]);

        return $dataProvider;
    }


    public function searchByGroup($params, $group_id = NULL)
    {
        if ($group_id === NULL) {
            // Get group id of current user.
            $group_id = DeelnemersEvent::find()
                ->select('group_ID')
                ->where('event_ID =:event_id and user_ID =:user_id')
                ->params([':event_id' => Yii::$app->user->identity->selected, ':user_id' => Yii::$app->user->id])
                ->one();
            $group_id = $groupModel->group_ID;
        }
        // Find all answers for founr group id
        $query = QrCheck::find()
            // ->select('qr_check_ID')
            ->where('event_ID=:event_id AND group_ID=:group_id')
            ->addParams([
                ':event_id' => Yii::$app->user->identity->selected,
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
            'qr_check_ID' => $this->qr_check_ID,
            'qr_ID' => $this->qr_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        return $dataProvider;
    }
}
