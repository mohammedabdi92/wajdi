<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InventoryOrder;

/**
 * InventoryOrderSearch represents the model behind the search form of `common\models\InventoryOrder`.
 */
class InventoryOrderSearch extends InventoryOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'supplier_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_cost'], 'number'],
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
        $query = InventoryOrder::find();


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
            'supplier_id' => $this->supplier_id,
            'total_cost' => $this->total_cost,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);
        if(!\Yii::$app->user->can('كل المحلات'))
        {
            $stores = \Yii::$app->user->identity->stores;
            $query->andWhere(['store_id'=>$stores]);

        }


        return $dataProvider;
    }
}
