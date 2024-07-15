<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Damaged;

/**
 * DamagedSearch represents the model behind the search form of `common\models\Damaged`.
 */
class DamagedSearch extends Damaged
{
    public $product_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'order_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['product_id', 'count', 'amount'], 'number'],
            [['product_name','store_id'], 'safe'],
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
            'order' => function (\yii\db\ActiveQuery $query) {
                $query->select(['store_id']);
            },'product'
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
            'damaged.id' => $this->id,
            'order.store_id' => $this->store_id,
            'damaged.status' => $this->status,
            'damaged.order_id' => $this->order_id,
            'damaged.product_id' => $this->product_id,
            'damaged.count' => $this->count,
            'damaged.amount' => $this->amount,
            'damaged.created_at' => $this->created_at,
            'damaged.created_by' => $this->created_by,
            'damaged.updated_at' => $this->updated_at,
            'damaged.updated_by' => $this->updated_by,
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
