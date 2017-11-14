<?php

namespace frontend\modules\cabinet\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cabinet\models\UserAdress;

/**
 * MOrderItemsSearch represents the model behind the search form about `common\models\MOrderItems`.
 */
class AdressSearch extends UserAdress
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['a_note'], 'string'],
         /*   [['c_id', 'u_id'], 'required'], */
            [['c_id', 'u_id', 'a_id'], 'integer'],
            [['a_city', 'a_adr'], 'string', 'max' => 250],
           
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
    public function search()
    {
        $query = UserAdress::find();
     
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->defaultOrder = ['a_id' => SORT_ASC];
        
        if ($this->u_id != 0) {
            
        $query->andFilterWhere([
            'u_id' => $this->u_id,            
        ]);
        
        }
        
        
        if ($this->c_id != 'empty') {
            $query->andFilterWhere(['c_id' => $this->c_id,]);
        }
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        

       

        return $dataProvider;
    }
}
