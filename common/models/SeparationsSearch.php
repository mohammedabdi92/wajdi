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
    public $product_from_name;
    public $product_to_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id_from', 'product_id_to', 'created_at', 'created_by', 'updated_at', 'updated_by', 'store_id'], 'integer'],
            [['count_from', 'count_to'], 'number'],
            [['product_from_name', 'product_to_name'], 'safe'],
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
            'separations.id' => $this->id,
            'separations.product_id_from' => $this->product_id_from,
            'separations.product_id_to' => $this->product_id_to,
            'separations.created_at' => $this->created_at,
            'separations.created_by' => $this->created_by,
            'separations.updated_at' => $this->updated_at,
            'separations.updated_by' => $this->updated_by,
            'separations.store_id' => $this->store_id,
            'separations.count_from' => $this->count_from,
            'separations.count_to' => $this->count_to,
        ]);
        if($this->product_from_name)
        {
            $query->joinWith('productFrom as product_from');
            $parts = preg_split('/\s+/', $this->product_from_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product_from.title )', "$part"]);
            }
        }
        if($this->product_to_name)
        {
            $query->joinWith('productTo as product_to');
            $parts = preg_split('/\s+/', $this->product_to_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product_to.title )', "$part"]);
            }
        }
        $query->orderBy(['separations.id'=>SORT_DESC]);

        return $dataProvider;
    }
}