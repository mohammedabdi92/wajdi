<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
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


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'store_id' , 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_amount'], 'number'],
            [['created_at_from','created_at_to','customer_name','product_id','created_at'], 'safe'],
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
        $query = Order::find();

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
            'order.id' => $this->id,
            'order.customer_id' => $this->customer_id,
            'order.total_amount' => $this->total_amount,
            'order.created_by' => $this->created_by,
            'order.updated_at' => $this->updated_at,
            'order.updated_by' => $this->updated_by,
            'order.isDeleted' => $this->isDeleted,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'order.created_at',strtotime( $this->created_at_from)]);
        }
        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'order.created_at',strtotime($this->created_at_to." 23:59:59") ]);
        }

        if($this->product_id)
        {
            $query->andWhere(' 1 = (select count(*) from order_product  where order_product.order_id = order.id  and order_product.product_id = '.$this->product_id.' limit 1  )');

        }
        if(!\Yii::$app->user->can('كل المحلات') && empty($this->store_id))
        {
            $stores = \Yii::$app->user->identity->stores;
            $query->andWhere(['order.store_id'=>$stores]);
        }else{
            $query->andFilterWhere(['order.store_id'=>$this->store_id]);
        }

        if($getSums)
        {

            $productQuery = clone $query;
            $productQuery->joinWith('products.product');
            $total_amount =  round($query->sum('total_amount'), 2);
            $total_dept =  round($productQuery->sum('(product.price * order_product.count) '),2);

            $this->total_dept  =  $total_dept ;
            $this->total_profit  =  $total_amount -  $total_dept ;
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
