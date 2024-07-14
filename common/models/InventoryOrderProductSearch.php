<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InventoryOrderProduct;

/**
 * InventoryOrderProductSearch represents the model behind the search form of `common\models\InventoryOrderProduct`.
 */
class InventoryOrderProductSearch extends InventoryOrderProduct
{
    public $sum_product_total_cost_final;
    public $sum_count;
    public $supplier_id;
    public $created_at_from ;
    public $created_at_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'inventory_order_id', 'product_id', 'created_by', 'updated_at', 'updated_by', 'isDeleted','store_id'], 'integer'],
            [['product_total_cost', 'product_cost', 'count'], 'number'],
            [['sum_product_total_cost_final','sum_count', 'created_at','supplier_id','created_at_from','created_at_to'], 'safe'],
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
        $query = InventoryOrderProduct::find();

        $query->joinWith('inventoryOrder');
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
            'inventory_order_product.id' => $this->id,
            'inventory_order_product.inventory_order_id' => $this->inventory_order_id,
            'inventory_order_product.product_id' => $this->product_id,
            'inventory_order_product.product_total_cost' => $this->product_total_cost,
            'inventory_order_product.product_cost' => $this->product_cost,
            'inventory_order_product.count' => $this->count,
            'inventory_order_product.store_id' => $this->store_id,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
            'inventory_order.supplier_id' => $this->supplier_id,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'inventory_order.created_at',strtotime( $this->created_at_from)]);
        }
        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'inventory_order.created_at',strtotime($this->created_at_to) ]);
        }

        if($getSums) {
            $this->sum_count = $query->sum('count');
            $this->sum_product_total_cost_final = $query->sum('product_total_cost_final');
        }

        return $dataProvider;
    }
}
