<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Separations;

/**
 * SeparationsSearch represents the model behind the search form of `common\models\Separations`.
 */
class SeparationsSearch extends Separations
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id_from', 'product_id_to', 'created_at', 'created_by', 'updated_at', 'updated_by', 'store_id'], 'integer'],
            [['count_from', 'count_to'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Separations::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'product_id_from' => $this->product_id_from,
            'product_id_to' => $this->product_id_to,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'store_id' => $this->store_id,
            'count_from' => $this->count_from,
            'count_to' => $this->count_to,
        ]);
        $query->orderBy(['id'=>SORT_DESC]);

        return $dataProvider;
    }
}