<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblNoodEnvelop;

/**
 * TblNoodEnvelopSearch represents the model behind the search form about `app\models\TblNoodEnvelop`.
 */
class TblNoodEnvelopSearch extends TblNoodEnvelop
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nood_envelop_ID', 'event_ID', 'route_ID', 'nood_envelop_volgorde', 'score', 'create_user_ID', 'update_user_ID'], 'integer'],
            [['nood_envelop_name', 'coordinaat', 'opmerkingen', 'create_time', 'update_time'], 'safe'],
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
        $query = TblNoodEnvelop::find();

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
            'nood_envelop_ID' => $this->nood_envelop_ID,
            'event_ID' => $this->event_ID,
            'route_ID' => $this->route_ID,
            'nood_envelop_volgorde' => $this->nood_envelop_volgorde,
            'score' => $this->score,
            'create_time' => $this->create_time,
            'create_user_ID' => $this->create_user_ID,
            'update_time' => $this->update_time,
            'update_user_ID' => $this->update_user_ID,
        ]);

        $query->andFilterWhere(['like', 'nood_envelop_name', $this->nood_envelop_name])
            ->andFilterWhere(['like', 'coordinaat', $this->coordinaat])
            ->andFilterWhere(['like', 'opmerkingen', $this->opmerkingen]);

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

		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
		$criteria->compare('nood_envelop_name',$this->nood_envelop_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('nood_envelop_volgorde',$this->nood_envelop_volgorde);
		$criteria->compare('coordinaat',$this->coordinaat,true);
		$criteria->compare('opmerkingen',$this->opmerkingen,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('create_user_ID',$this->create_user_ID);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('update_user_ID',$this->update_user_ID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function searchHints($event_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('nood_envelop_ID',$this->nood_envelop_ID);
		$criteria->compare('nood_envelop_name',$this->nood_envelop_name,true);
		$criteria->compare('event_ID',$this->event_ID);
		$criteria->condition = 'event_ID=:event_id';
		$criteria->params=array(':event_id'=>$event_id);
		$criteria->order= 'route_ID ASC, nood_envelop_volgorde ASC';
		$criteria->compare('route_ID',$this->route_ID);
		$criteria->compare('nood_envelop_volgorde',$this->nood_envelop_volgorde);
		$criteria->compare('coordinaat',$this->coordinaat,true);
		$criteria->compare('opmerkingen',$this->opmerkingen,true);
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
