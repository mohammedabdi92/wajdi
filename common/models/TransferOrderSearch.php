<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TransferOrder;

/**
 * TransferOrderSearch represents the model behind the search form of `common\models\TransferOrder`.
 */
class TransferOrderSearch extends TransferOrder
{
    public $product_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'from', 'product_id','to', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted','count'], 'integer'],
            [['product_name'], 'safe'],
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
        $query = TransferOrder::find();
        $query->joinWith('product');
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
            'from' => $this->from,
            'to' => $this->to,
            'product_id' => $this->product_id,
            'status' => $this->status,
            'count' => $this->count,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);
        if($this->product_name)
        {
            $parts = preg_split('/\s+/', $this->product_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product.title )', "$part"]);
            }
        }
        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
}
