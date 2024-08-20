<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Damaged;
use yii\db\Expression;

/**
 * DamagedSearch represents the model behind the search form of `common\models\Damaged`.
 */
class DamagedSearch extends Damaged
{
    public $product_name;
    public $customer_name;
    public $supplier_name;

    public $created_at_from;
    public $created_at_to;
    public $updated_at_from;
    public $updated_at_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'order_id', 'created_by', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [['product_name','store_id','customer_name','supplier_name','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from'], 'safe'],
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
        $query = Damaged::find();

        $query->select(
            "damaged.*,order.store_id as store_id"
        );
        $query->joinWith([
            'order' ,'product','inventoryOrder'
        ]);
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
            $users =  Customer::find()->select('id')->where(" name like '%$this->customer_name%' ")->column();
           if($users){
               $query->andFilterWhere(['order.customer_id' => $users]);
           }
        }
        if(!empty($this->supplier_name))
        {
            $users2 =  Supplier::find()->select('id')->where(" name like '%$this->supplier_name%' ")->column();
           if($users2){
               $query->andFilterWhere(['inventory_order.supplier_id' => $users2]);
           }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'damaged.id' => $this->id,
            'order.store_id' => $this->store_id,
            'damaged.status' => $this->status,
            'damaged.order_id' => $this->order_id,
            'damaged.product_id' => $this->product_id,
            'damaged.count' => $this->count,
            'damaged.amount' => $this->amount,
            'damaged.created_by' => $this->created_by,
            'damaged.updated_by' => $this->updated_by,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'damaged.created_at',strtotime( $this->created_at_from)]);

        }
       

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'damaged.created_at',strtotime( $this->created_at_to)]);
        }
        if($this->updated_at_from)
        {
            $query->andFilterWhere(['>=', 'damaged.updated_at',strtotime( $this->updated_at_from)]);

        }

        if($this->updated_at_to)
        {
            $query->andFilterWhere(['<=', 'damaged.updated_at',strtotime( $this->updated_at_to)]);
        }
        if($this->product_name)
        {
            $parts = preg_split('/\s+/', $this->product_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product.title )', "$part"]);
            }
        }
        $query->orderBy(new Expression('CASE WHEN damaged.status = 4 THEN 1 ELSE 0 END, damaged.id DESC'));
        return $dataProvider;
    }
}
