<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    public $created_at_from;
    public $created_at_to;
    public $total_amount_without_discount_sum;
    public $total_discount_sum ;
    public $debt_sum ;
    public $repayment_sum ;
    public $total_amount_sum ;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'store_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['total_amount'], 'number'],
            [['created_at_from','created_at_to'], 'safe'],
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
        $query = Order::find();

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
            'customer_id' => $this->customer_id,
            'total_amount' => $this->total_amount,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'isDeleted' => $this->isDeleted,
        ]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'order.created_at',strtotime( $this->created_at_from)]);
        }
        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'order.created_at',strtotime($this->created_at_to) ]);
        }

        if(!\Yii::$app->user->can('كل المحلات'))
        {
            $stores = \Yii::$app->user->identity->stores;
            $query->andWhere(['store_id'=>$stores]);

        }

        if($getSums)
        {
            $this->total_amount_without_discount_sum = $query->sum('total_amount_without_discount');
            $this->debt_sum = $query->sum('debt');
            $this->repayment_sum = $query->sum('repayment');
            $this->total_amount_sum = round($query->sum('total_amount'), 2);
            $this->total_discount_sum = $query->sum('total_discount') + $query->sum('total_price_discount_product') ;
        }

        return $dataProvider;
    }
}
