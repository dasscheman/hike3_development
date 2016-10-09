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
    
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$user_id = Yii::app()->user->id; 
	    $criteria=new CDbCriteria;
	   
	    $criteria->compare('friend_list_ID',$this->friend_list_ID);
	    $criteria->compare('user_ID',$this->user_ID);
	    $criteria->compare('friends_with_user_ID',$this->friends_with_user_ID);
	    $criteria->compare('status',$this->status);
	    $criteria->compare('create_time',$this->create_time,true);
	    $criteria->compare('create_user_ID',$this->create_user_ID);
	    $criteria->compare('update_time',$this->update_time,true);
	    $criteria->compare('update_user_ID',$this->update_user_ID);
	    return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
	    ));
	}

}
