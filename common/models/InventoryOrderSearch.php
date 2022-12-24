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
    public $supplier_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'supplier_id','store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_cost'], 'number'],
            [['supplier_name'], 'safe'],
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
        if(!empty($this->supplier_name))
        {
            $user =  Supplier::find()->where(" name like '%$this->supplier_name%' ")->one();
            if($user){
                $this->supplier_id =$user->id;
            }
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
        $query->orderBy(['id'=>SORT_DESC]);

        return $dataProvider;
    }
}
