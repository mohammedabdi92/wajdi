<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Inventory;
use yii\db\Expression;

/**
 * InventorySearch represents the model behind the search form of `common\models\Inventory`.
 */
class InventorySearch extends Inventory
{

    public $product_name;
    public $sum_price;
    public $sum_price_1;
    public $sum_price_2;
    public $sum_price_3;
    public $sum_price_4;
    public $sum_count;
    public $category_id;
    public $stagnant_month;
    public $is_stagnant;
    public $created_at_from;
    public $created_at_to;
    public $updated_at_from;
    public $updated_at_to;
    
    public $created_at_range;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'store_id', 'created_by', 'updated_by', 'isDeleted','available_status','stagnant_month','is_stagnant'], 'integer'],
            [['last_product_cost', 'count'], 'number'],
            [['sum_price','sum_price_1', 'sum_price_2', 'sum_price_3', 'sum_price_4','sum_count','product_name','category_id','created_at_range','created_at','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from','item_code'], 'safe'],
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
        $query = Inventory::find();
        $query->select(
            new Expression("(CASE 
            WHEN ( inventory.count = 0 ) THEN 3 
            WHEN ( min_product_count.count IS NOT NULL and min_product_count.count > inventory.count ) THEN 2 ELSE 1 END
            ) AS available_status ,product.item_code as item_code,inventory.*")
        );
        $query->joinWith([
            'product' => function (\yii\db\ActiveQuery $query) {
                $query->select(['id','category_id','price','price_1','price_2','price_3','price_4','count_type','title','item_code']);
            },
            'minProductCount' => function (\yii\db\ActiveQuery $query) {
                $query->select(['count']);
            }
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


        // grid filtering conditions
        $query->andFilterWhere([
            'inventory.id' => $this->id,
            'inventory.product_id' => $this->product_id,
            'inventory.last_product_cost' => $this->last_product_cost,
            'inventory.count' => $this->count,
            'inventory.created_by' => $this->created_by,
            'inventory.updated_by' => $this->updated_by,
            'inventory.isDeleted' => $this->isDeleted,
            'product.category_id' => $this->category_id,
        ]);
        
        if(!empty($this->stagnant_month))
        {
           $date_stagnant_month = strtotime("-$this->stagnant_month month");
            $query->select(
                new Expression("(CASE WHEN ( inventory.count = 0 ) THEN 3  WHEN ( min_product_count.count IS NOT NULL and min_product_count.count > inventory.count ) THEN 2 ELSE 1 END ) AS available_status ,inventory.* , 
                (CASE  WHEN ( select order_product.id from order_product WHERE order_product.product_id =  inventory.product_id  and order_product.store_id =  inventory.store_id  and  created_at > $date_stagnant_month  limit 1 ) THEN 1  ELSE 0 END ) AS is_stagnant")
            );
            $query->andFilterHaving(["is_stagnant"=>0]);
        }
        if(empty($this->store_id) && !\Yii::$app->user->can('جميع المحلات مواد الافرع المخزن'))
        {
            $query->andFilterWhere( ['inventory.store_id' => \Yii::$app->user->identity->stores]);
        }
        if(!empty($this->store_id))
        {
            $query->andFilterWhere( ['inventory.store_id' => $this->store_id]);
        }

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'inventory.created_at',strtotime( $this->created_at_from)]);

        }
        if($this->item_code)
        {
            $query->andFilterWhere(['like', 'LOWER( product.item_code )', "$this->item_code"]);
        }       

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'inventory.created_at',strtotime( $this->created_at_to)]);
        }
        if($this->updated_at_from)
        {
            $query->andFilterWhere(['>=', 'inventory.updated_at',strtotime( $this->updated_at_from)]);

        }

        if($this->updated_at_to)
        {
            $query->andFilterWhere(['<=', 'inventory.updated_at',strtotime( $this->updated_at_to)]);
        }

        if($this->product_name)
        {
            $parts = preg_split('/\s+/', $this->product_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product.title )', "$part"]);
            }
        }
        if(!empty($this->available_status))
        {
            $query->andFilterHaving(['available_status' =>$this->available_status]);
        }


        if($getSums)
        {
            $this->sum_price = $query->sum('(product.price * inventory.count)');
            $this->sum_price_1 = $query->sum('(product.price_1 * inventory.count)');
            $this->sum_price_2 = $query->sum('(product.price_2 * inventory.count)');
            $this->sum_price_3 = $query->sum('(product.price_3 * inventory.count)');
            $this->sum_price_4 = $query->sum('(product.price_4 * inventory.count)');
            $this->sum_count = $query->sum('inventory.count');
        }
//  print_r($query->createCommand()->getRawSql());die;

        return $dataProvider;
    }
}
