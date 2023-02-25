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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'store_id', 'created_by', 'updated_at', 'updated_by', 'isDeleted','available_status'], 'integer'],
            [['last_product_cost', 'count'], 'number'],
            [['sum_price','sum_price_1', 'sum_price_2', 'sum_price_3', 'sum_price_4','sum_count','product_name','created_at','category_id'], 'safe'],
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
            new Expression("(CASE WHEN ( inventory.count = 0 ) THEN 3 WHEN ( min_product_count.count IS NOT NULL and min_product_count.count > inventory.count ) THEN 2 ELSE 1 END) AS available_status ,inventory.*,product.*"));
        $query->joinWith('product');
        $query->joinWith('minProductCount');
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
            'inventory.updated_at' => $this->updated_at,
            'inventory.updated_by' => $this->updated_by,
            'inventory.isDeleted' => $this->isDeleted,
            'product.category_id' => $this->category_id,
        ]);
        if(empty($this->store_id) && !\Yii::$app->user->can('جميع المحلات مواد الافرع المخزن'))
        {
            $query->andFilterWhere( ['store_id' => \Yii::$app->user->identity->stores]);
        }
        if(!empty($this->store_id))
        {
            $query->andFilterWhere( ['store_id' => $this->store_id]);
        }

        if(!empty($this->created_at))
        {
            $query->andWhere(['>=','inventory.created_at',strtotime($this->created_at.' 00:00:00')])->andWhere(['<=','inventory.created_at',strtotime($this->created_at.' 24:59:59')]);
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
        return $dataProvider;
    }
}
