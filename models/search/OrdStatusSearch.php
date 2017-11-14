<?php

namespace frontend\modules\cabinet\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cabinet\models\OrdStatus;

/**
 * MOrderItemsSearch represents the model behind the search form about `common\models\MOrderItems`.
 */
class OrdStatusSearch extends OrdStatus
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['s_id'], 'integer'],
            [['s_name', 's_color'], 'string', 'max' => 250],
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
        $query = OrdStatus::find();
      /*  $query->joinWith(['user']);*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->defaultOrder = ['s_id' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            's_id' => $this->s_id,
            's_name' => $this->s_name,
        ]);

        $query->andFilterWhere(['like', 's_name', $this->s_name])
            ->andFilterWhere(['like', 's_color', $this->s_color]);

        return $dataProvider;
    }
}
