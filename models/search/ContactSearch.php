<?php

namespace frontend\modules\cabinet\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cabinet\models\UserContacts;

/**
 * MOrderItemsSearch represents the model behind the search form about `common\models\MOrderItems`.
 */
class ContactSearch extends UserContacts
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['c_id', 'integer'],
             [['u_id'], 'integer'],
            [['c_note'], 'string'],
            [['c_name', 'c_email', 'c_phone', 'c_post'], 'string', 'max' => 250],
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
        $query = UserContacts::find();
      /*  $query->joinWith(['user']);*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->defaultOrder = ['c_id' => SORT_ASC];
        
        if ($this->u_id != 0) {
        
        $query->andFilterWhere([
            'u_id' => $this->u_id,            
        ]);
        
        }
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        

       

        return $dataProvider;
    }
}
