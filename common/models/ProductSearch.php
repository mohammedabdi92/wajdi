<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form of `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'count_type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['title','price_1','price_2','price_3','price_4','status','item_code'], 'safe'],
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
        $query = Product::find();

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
            'id' => $this->id,
            'category_id' => $this->category_id,
            'count_type' => $this->count_type,
            'status' => $this->status,
            'price_1' => $this->price_1,
            'price_2' => $this->price_2,
            'price_3' => $this->price_3,
            'price_4' => $this->price_4,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);

        if($this->title)
        {
            $parts = preg_split('/\s+/', $this->title);
            foreach ($parts as $part){
                $query->andFilterWhere(['like', 'LOWER( title )', "$part"]);
            }
        }
        if($this->item_code)
        {
            $query->andFilterWhere(['like', 'LOWER( item_code )', "$this->item_code"]);
        }       

        return $dataProvider;
    }
}
