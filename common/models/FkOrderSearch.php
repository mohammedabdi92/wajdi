<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FkOrder;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class FkOrderSearch extends FkOrder
{
    public $product_id;
    public $created_at_from;
    public $created_at_to;
    public $total_amount_without_discount_sum;
    public $total_discount_sum ;
    public $debt_sum ;
    public $repayment_sum ;
    public $total_amount_sum ;
    public $customer_name ;
    public $total_profit ;
    public $total_dept;
    public $total_returns_amount;
    public $total_profit_returns_amount;
    public $returns_amount;




    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'store_id' , 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_amount'], 'number'],
            [['created_at_from','created_at_to','customer_name','product_id','created_at','returns_amount'], 'safe'],
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
        $query = FkOrder::find();

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

        if(!empty($this->customer_name))
        {
            $user =  Customer::find()->where(" name like '%$this->customer_name%' ")->one();
           if($user){
               $this->customer_id =$user->id;
           }else{
               $this->supplier_id = 1000000000000;
           }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'fk_order.id' => $this->id,
            'fk_order.customer_id' => $this->customer_id,
            'fk_order.total_amount' => $this->total_amount,
            'fk_order.created_by' => $this->created_by,
            'fk_order.updated_at' => $this->updated_at,
            'fk_order.updated_by' => $this->updated_by,
            'fk_order.isDeleted' => $this->isDeleted,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'fk_order.created_at',strtotime( $this->created_at_from)]);
        }
        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'fk_order.created_at',strtotime($this->created_at_to." 23:59:59") ]);
        }

        if($this->product_id)
        {
            $query->andWhere(' 1 = (select count(*) from fk_order_product  where fk_order_product.order_id = fk_order.id  and fk_order_product.product_id = '.$this->product_id.' limit 1  )');

        }
        if(!\Yii::$app->user->can('كل المحلات') && empty($this->store_id))
        {
            $stores = \Yii::$app->user->identity->stores;
            $query->andWhere(['fk_order.store_id'=>$stores]);
        }else{
            $query->andFilterWhere(['fk_order.store_id'=>$this->store_id]);
        }

        if($getSums)
        {
            $query->select('*,(select sum(returns.amount) from returns where returns.order_id = order.id) as returns_amount');

            $productQuery = clone $query;
            $productQuery->joinWith('products.product');
            $this->total_returns_amount = $productQuery->sum('(select sum(returns.amount) from returns where returns.order_id = order.id)')  ;
            $total_dept_returns_amount = $productQuery->sum('(select sum(returns.count * product.price) from returns where returns.order_id = order.id)')  ;
            $total_amount =  round($query->sum('total_amount'), 2);
            $total_dept =  round($productQuery->sum('(product.price * fk_order_product.count) '),2);


            $this->total_profit_returns_amount  =  $this->total_returns_amount  - $total_dept_returns_amount ;
            $this->total_dept  =  $total_dept ;
            $this->total_profit  =  $total_amount -  $total_dept - $this->total_profit_returns_amount;
            $this->total_amount_without_discount_sum = $query->sum('total_amount_without_discount');
            $this->debt_sum = $query->sum('debt');
            $this->repayment_sum = $query->sum('repayment');
            $this->total_amount_sum = $total_amount;
            $this->total_discount_sum = $query->sum('total_discount') + $query->sum('total_price_discount_product') ;
        }
        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
    public function getProductTitle()
    {
        return $this->product ? $this->product->title : '';
    }
}
