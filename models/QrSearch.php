<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblQr;

/**
 * TblQrSearch represents the model behind the search form about `app\models\TblQr`.
 */
class TblQrSearch extends TblQr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qr_ID', 'event_ID', 'route_ID', 'qr_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['qr_name', 'qr_code', 'create_time', 'update_time'], 'safe'],
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
        $query = TblQr::find();

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
            'qr_ID' => $this->qr_ID,
            'event_ID' => $this->event_ID,
            'route_ID' => $this->route_ID,
            'qr_volgorde' => $this->qr_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'qr_name', $this->qr_name])
            ->andFilterWhere(['like', 'qr_code', $this->qr_code]);

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
//		$criteria->compare('qr_ID',$this->qr_ID);
//		$criteria->compare('qr_name',$this->qr_name,true);
//		$criteria->compare('qr_code',$this->qr_code,true);
//		$criteria->compare('event_ID',$this->event_ID);
//		$criteria->compare('route_ID',$this->route_ID);
//		$criteria->compare('qr_volgorde',$this->qr_volgorde);
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
    
}
