<?php

namespace frontend\modules\cabinet\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cabinet\models\UserGroups;

/**
 * MOrderItemsSearch represents the model behind the search form about `common\models\MOrderItems`.
 */
class UsrGroupsSearch extends UserGroups
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ug_id', 'ug_skidka'], 'integer'],
            [['ug_name'], 'string', 'max' => 250],
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
        $query = UserGroups::find();
      /*  $query->joinWith(['user']);*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->defaultOrder = ['ug_id' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ug_id' => $this->ug_id,
            'ug_name' => $this->ug_name,
        ]);

        $query->andFilterWhere(['like', 'ug_name', $this->ug_name])
            ->andFilterWhere(['like', 'ug_skidka', $this->ug_skidka]);

        return $dataProvider;
    }
}
