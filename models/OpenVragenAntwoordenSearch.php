<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OpenVragenAntwoorden;

/**
 * TblOpenVragenAntwoordenSearch represents the model behind the search form about `app\models\OpenVragenAntwoorden`.
 */
class OpenVragenAntwoordenSearch extends OpenVragenAntwoorden
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['open_vragen_antwoorden_ID', 'open_vragen_ID', 'event_ID', 'group_ID', 'checked', 'correct', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['antwoord_spelers', 'create_time', 'update_time'], 'safe'],
            [
                [
                    'open_vragen_antwoorden_ID', 'event_ID', 'open_vragen_ID',
                    'group_ID', 'antwoord_spelers', 'checked', 'correct', 'create_time', 
                    'create_user_ID', 'update_time', 'update_user_ID', 'group_name'
                ], 
                'safe', 'on'=>'search'
            ],
            [
                [
                    'event_ID', 'antwoord_spelers', 'checked', 'correct', 'create_time', 
                    'create_user_ID', 'update_time', 'update_user_ID', 'group_name', 
                    'open_vraag', 'open_vragen_name', 'goede_antwoord', 'username', 'score'
                ], 
                'safe', 'on'=>'searchAnswered'
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
    public function search($params)
    {
        $query = OpenVragenAntwoorden::find();

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
            'open_vragen_antwoorden_ID' => $this->open_vragen_antwoorden_ID,
            'open_vragen_ID' => $this->open_vragen_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'checked' => $this->checked,
            'correct' => $this->correct,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'antwoord_spelers', $this->antwoord_spelers]);

        return $dataProvider;
    }
    
    public function searchAnswered($event_id)
    {
        $query = OpenVragenAntwoorden::find();
        $query->with(['openVragen', 'group', 'createUser']);
        $query->onCondition(
            'event_ID = :event_id', 
            [':event_id'=>Yii::app()->user->event_id]);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['t.create_time'=>SORT_ASC]]
        ]);
        $dataProvider->sort->attributes['street'] = 
            [
                'asc' => ['group.group_name' => SORT_ASC],
                'desc' => ['group.group_name' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['district'] = 
            [
                'asc' => ['openVragen.open_vragen_name' => SORT_ASC],
                'desc' => ['openVragen.open_vragen_name' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['street'] = 
            [
                'asc' => ['openVragen.vraag' => SORT_ASC],
                'desc' => ['openVragen.vraag' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['district'] = 
            [
                'asc' => ['t.antwoord_spelers' => SORT_ASC],
                'desc' => ['t.antwoord_spelers' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['street'] = 
            [
                'asc' => ['openVragen.goede_antwoord' => SORT_ASC],
                'desc' => ['openVragen.goede_antwoord' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['district'] = 
            [
                'asc' => ['createUser.username' => SORT_ASC],
                'desc' => ['createUser.username' => SORT_DESC],
            ];
        $dataProvider->sort->attributes['district'] = 
            [
                'asc' => ['openVragen.score' => SORT_ASC],
                'desc' => ['openVragen.score' => SORT_DESC],
            ];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'open_vragen_antwoorden_ID' => $this->open_vragen_antwoorden_ID,
            'open_vragen_ID' => $this->open_vragen_ID,
            'event_ID' => $this->event_ID,
            'group_ID' => $this->group_ID,
            'checked' => $this->checked,
            'correct' => $this->correct,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'antwoord_spelers', $this->antwoord_spelers]);

        return $dataProvider;
	    // Warning: Please modify the following code to remove attributes that
	    // should not be searched.

	    $criteria=new CDbCriteria;

            $criteria->with=array('openVragen', 'group', 'createUser');
	    $criteria->compare('t.event_ID', $event_id);
	    $criteria->compare('antwoord_spelers',$this->antwoord_spelers);
	    $criteria->compare('checked',$this->checked);
	    $criteria->compare('correct',$this->correct);
	    $criteria->compare('t.create_time',$this->create_time,true);
	    $criteria->compare('create_user_ID',$this->create_user_ID);
	    $criteria->compare('update_time',$this->update_time,true);
	    $criteria->compare('update_user_ID',$this->update_user_ID);
		$criteria->compare('group.group_name', $this->group_name,true);

		$criteria->compare('openVragen.open_vragen_name', $this->open_vragen_name,true);
		$criteria->compare('openVragen.vraag', $this->open_vraag,true);
		$criteria->compare('openVragen.goede_antwoord', $this->goede_antwoord,true);
		$criteria->compare('openVragen.score', $this->score,true);
		$criteria->compare('createUser.username', $this->username,true);

		$sort = new CSort();
		$sort->attributes = array(
			//'defaultOrder'=>'t.create_time ASC',
			'create_time'=>array(
				'asc'=>'t.create_time',
				'desc'=>'t.create_time asc',
			),
			'group_name'=>array(
				'asc'=>'group.group_name',
				'desc'=>'group.group_name desc',
			),
			'open_vragen_name'=>array(
				'asc'=>'openVragen.open_vragen_name',
				'desc'=>'openVragen.open_vragen_name desc',
			),
			'open_vraag'=>array(
				'asc'=>'openVragen.vraag',
				'desc'=>'openVragen.vraag desc',
			),


			'antwoord_spelers'=>array(
				'asc'=>'t.antwoord_spelers',
				'desc'=>'t.antwoord_spelers desc',
			),
			'goede_antwoord'=>array(
				'asc'=>'openVragen.goede_antwoord',
				'desc'=>'openVragen.goede_antwoord desc',
			),
			'username'=>array(
				'asc'=>'createUser.username',
				'desc'=>'createUser.username desc',
			),
			'score'=>array(
				'asc'=>'openVragen.score',
				'desc'=>'openVragen.score desc',
			),
		);

	    return new CActiveDataProvider($this, array(
		    'criteria'=>$criteria,
			'sort'=>$sort
	    ));
    }
}
