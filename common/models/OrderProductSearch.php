<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderProduct;

/**
 * OrderProductSearch represents the model behind the search form of `common\models\OrderProduct`.
 */
class OrderProductSearch extends OrderProduct
{
    public $sum_product_price;
    public $sum_count;
    public $sum_total_product_amount;
    public $sum_discount;
    public $sum_total_amount_w_discount;
    public $sum_profit;
    public $created_at_from ;
    public $created_at_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'product_id', 'count_type', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['count'], 'number'],
            [['created_at_from','created_at_to', 'created_at'],'safe']
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
    public function search($params,$getSums=false)
    {
        $query = OrderProduct::find();

        // add conditions that should always apply here
        $query->joinWith('product');

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
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'count' => $this->count,
            'count_type' => $this->count_type,
            'product.created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'order_product.created_at',strtotime( $this->created_at_from)]);
        }
        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'order_product.created_at',strtotime($this->created_at_to) ]);
        }

        if($getSums)
        {
            $this->sum_product_price = $query->sum('(product.price * count )');

            $this->sum_count = $query->sum('count');
            $this->sum_total_product_amount = $query->sum('total_product_amount');
            $this->sum_discount = $query->sum('discount');

            $this->sum_total_amount_w_discount = $this->sum_total_product_amount - $this->sum_discount;
            $this->sum_profit = $this->sum_total_amount_w_discount - $this->sum_product_price;

        }

        return $dataProvider;
    }
}
