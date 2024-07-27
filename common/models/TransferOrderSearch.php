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
            [['id', 'from', 'product_id','to', 'status', 'created_by', 'updated_by', 'isDeleted','count'], 'integer'],
            [['product_name','created_at','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from'], 'safe'],
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
            'transfer_order.id' => $this->id,
            'transfer_order.from' => $this->from,
            'transfer_order.to' => $this->to,
            'transfer_order.product_id' => $this->product_id,
            'transfer_order.status' => $this->status,
            'transfer_order.count' => $this->count,
            'transfer_order.created_by' => $this->created_by,
            'transfer_order.updated_by' => $this->updated_by,
            'transfer_order.isDeleted' => $this->isDeleted,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'transfer_order.created_at',strtotime( $this->created_at_from)]);

        }
       

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'transfer_order.created_at',strtotime( $this->created_at_to)]);
        }
        if($this->updated_at_from)
        {
            $query->andFilterWhere(['>=', 'transfer_order.updated_at',strtotime( $this->updated_at_from)]);

        }

        if($this->updated_at_to)
        {
            $query->andFilterWhere(['<=', 'transfer_order.updated_at',strtotime( $this->updated_at_to)]);
        }

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
