<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Route;

/**
 * TblRouteSearch represents the model behind the search form about `app\models\TblRoute`.
 */
class RouteSearch extends Route
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route_ID', 'event_ID', 'route_volgorde', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['route_name', 'day_date', 'create_time', 'update_time'], 'safe'],
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
        $query = TblRoute::find();

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
            'route_ID' => $this->route_ID,
            'event_ID' => $this->event_ID,
            'day_date' => $this->day_date,
            'route_volgorde' => $this->route_volgorde,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'route_name', $this->route_name]);

        return $dataProvider;
    }
    
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('route_name',$this->route_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->compare('day_date',$this->day_date,true);
		$criteria->compare('route_volgorde',$this->route_volgorde);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function searchRoute($event_id, $startDate)
    {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->compare('day_date',$this->day_date,true);
		$criteria->condition = 'event_ID=:event_id AND day_date=:date';
		$criteria->params=array(':event_id'=>$event_id,
							    ':date'=>$startDate);
		$criteria->order= 'route_volgorde ASC';
		$criteria->compare('route_volgorde',$this->route_volgorde);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageSize'=>50)
		));
    }

    
}
