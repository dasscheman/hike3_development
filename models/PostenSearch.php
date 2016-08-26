<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblPosten;

/**
 * TblPostenSearch represents the model behind the search form about `app\models\TblPosten`.
 */
class TblPostenSearch extends TblPosten
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_ID', 'event_ID', 'post_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['post_name', 'date', 'create_time', 'update_time'], 'safe'],
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
        $query = TblPosten::find();

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
            'post_ID' => $this->post_ID,
            'event_ID' => $this->event_ID,
            'date' => $this->date,
            'post_volgorde' => $this->post_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'post_name', $this->post_name]);

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
//		$criteria->compare('post_ID',$this->post_ID);
//		$criteria->compare('post_name',$this->post_name,true);
//		$criteria->compare('event_ID',$this->event_ID);
//		$criteria->compare('date',$this->date,true);
//		$criteria->compare('post_volgorde',$this->post_volgorde);
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

	public function searchPosten($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('post_ID',$this->post_ID);
		$criteria->compare('post_name',$this->post_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->condition = 'event_ID=:event_id';
		$criteria->params=array(':event_id'=>$event_id);
		$criteria->order= 'date ASC, post_volgorde ASC';
		$criteria->compare('date',$this->date,true);
		$criteria->compare('post_volgorde',$this->post_volgorde);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function searchPostDate($event_id, $startDate)
    {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('post_ID',$this->post_ID);
		$criteria->compare('post_name',$this->post_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->condition = 'event_ID=:event_id AND date=:date';
		$criteria->params=array(':event_id'=>$event_id,
							    ':date'=>$startDate);
		$criteria->order= 'date ASC, post_volgorde ASC';
		$criteria->compare('date',$this->date,true);
		$criteria->compare('post_volgorde',$this->post_volgorde);
		$criteria->compare('score',$this->score);
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
