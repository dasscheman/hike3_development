<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblOpenVragen;

/**
 * TblOpenVragenSearch represents the model behind the search form about `app\models\TblOpenVragen`.
 */
class TblOpenVragenSearch extends TblOpenVragen
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
        $query = TblOpenVragen::find();

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
    
    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
//	public function search()
//	{
//		// Warning: Please modify the following code to remove attributes that
//		// should not be searched.
//
//		$criteria=new CDbCriteria;
//
//		$criteria->compare('open_vragen_ID',$this->open_vragen_ID);
//		$criteria->compare('open_vragen_name',$this->open_vragen_name,true);
//		$criteria->compare('event_ID',$this->event_ID);
//		$criteria->compare('route_ID',$this->route_ID);
//		$criteria->compare('vraag_volgorde',$this->vraag_volgorde);
//		$criteria->compare('omschrijving',$this->omschrijving,true);
//		$criteria->compare('vraag',$this->vraag,true);
//		$criteria->compare('goede_antwoord',$this->goede_antwoord,true);
//		$criteria->compare('score',$this->score);
//		$criteria->compare('create_time',$this->create_time,true);
//		$criteria->compare('create_user_ID',$this->create_user_ID);
//		$criteria->compare('update_time',$this->update_time,true);
//		$criteria->compare('update_user_ID',$this->update_user_ID);
//
//		return new CActiveDataProvider($this, array(
//			'criteria'=>$criteria,
//		));
//	}
    
	public function searchOpenVragen($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('open_vragen_ID',$this->open_vragen_ID);
		$criteria->compare('open_vragen_name',$this->open_vragen_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->condition = 'event_ID=:event_id';
		$criteria->params=array(':event_id'=>$event_id);
		$criteria->order= 'route_ID ASC, vraag_volgorde ASC';
		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('vraag_volgorde',$this->vraag_volgorde);
		$criteria->compare('omschrijving',$this->omschrijving,true);
		$criteria->compare('vraag',$this->vraag,true);
		$criteria->compare('goede_antwoord',$this->goede_antwoord,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
}
