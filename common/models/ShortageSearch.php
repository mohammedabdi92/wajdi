<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Shortage;

/**
 * ShortageSearch represents the model behind the search form of `common\models\Shortage`.
 */
class ShortageSearch extends Shortage
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
            [['id', 'store_id', 'product_id', 'count', 'created_by', 'updated_by'], 'integer'],
            [['created_at','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from','note','product_name'], 'safe'],
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
        $query = Shortage::find();

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
            'shortage.id' => $this->id,
            'shortage.store_id' => $this->store_id,
            'shortage.product_id' => $this->product_id,
            'shortage.count' => $this->count,
            'shortage.created_by' => $this->created_by,
            'shortage.updated_by' => $this->updated_by,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'shortage.created_at',strtotime( $this->created_at_from)]);

        }
       

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'shortage.created_at',strtotime( $this->created_at_to)]);
        }
        if($this->updated_at_from)
        {
            $query->andFilterWhere(['>=', 'shortage.updated_at',strtotime( $this->updated_at_from)]);

        }

        if($this->updated_at_to)
        {
            $query->andFilterWhere(['<=', 'shortage.updated_at',strtotime( $this->updated_at_to)]);
        }

        if($this->product_name)
        {
            $parts = preg_split('/\s+/', $this->product_name);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( product.title )', "$part"]);
            }
        }
        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
