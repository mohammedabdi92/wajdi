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
            [['id', 'order_id','store_id', 'product_id', 'count_type', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['count'], 'number'],
            [['created_at_from','created_at_to', 'created_at','store_id'],'safe']
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
        $query->joinWith([
            'product' => function (\yii\db\ActiveQuery $query) {
                $query->select(['id', 'price','title']);
            }]);
        $query->joinWith('order');

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
            'order_product.store_id' => $this->store_id,
            'count' => $this->count,
            'count_type' => $this->count_type,
            'order_product.created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);

        if($this->product_id)
        {
            $query->andFilterWhere(['product_id' => $this->product_id,]);

        }
        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'order.created_at',strtotime( $this->created_at_from)]);

        }

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'order.created_at',strtotime( $this->created_at_to)]);
        }

//        print_r($query->createCommand()->rawSql);die;
        if($getSums)
        {
            $sumQuery = clone $query ;
            $this->sum_product_price = $sumQuery->sum('(product.price * count )');

            $this->sum_count = $sumQuery->sum('count');
            $this->sum_total_product_amount = $sumQuery->sum('total_product_amount');
            $this->sum_discount = $sumQuery->sum('discount') + $sumQuery->select('order.*')->groupBy('order.id')->sum('total_discount');

            $this->sum_total_amount_w_discount = $this->sum_total_product_amount - $this->sum_discount;
            $this->sum_profit = $this->sum_total_amount_w_discount - $this->sum_product_price;

            \Yii::info('sum_product_price :-'.$this->sum_product_price);
            \Yii::info('sum_count :- '.$this->sum_count);
            \Yii::info('sum_total_product_amount :- '.$this->sum_total_product_amount);
            \Yii::info('sum_discount :- '.$this->sum_discount);
            \Yii::info('sum_total_amount_w_discount :- '.$this->sum_total_amount_w_discount);
            \Yii::info('sum_profit :- '.$this->sum_profit);

        }
       

        return $dataProvider;
    }
}
