<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form of `common\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    
    public $customerName;
    public $created_at_from;
    public $created_at_to;
    // public $customer.name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'order_id', 'type', 'created_by', 'updated_at', 'updated_by', 'isDeleted'], 'integer'],
            [['amount'], 'number'],
            [['note','customerName','created_at_from','created_at_to'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['customerName'] = 'العميل'; 

        return $attributeLabels;
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
        $query = Transactions::find();

        $query->joinWith([
            'customer' => function (\yii\db\ActiveQuery $query) {
                $query->select(['id', 'name']);
            }]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            print_r($this->getErrors());die;
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transactions.id' => $this->id,
            'transactions.customer_id' => $this->customer_id,
            'transactions.order_id' => $this->order_id,
            'transactions.amount' => $this->amount,
            'transactions.type' => $this->type,
            'transactions.created_at' => $this->created_at,
            'transactions.created_by' => $this->created_by,
            'transactions.updated_at' => $this->updated_at,
            'transactions.updated_by' => $this->updated_by,
            'transactions.isDeleted' => $this->isDeleted,
        ]);
        $query->andFilterWhere(['like', 'customer.name', $this->customerName]);

        $query->andFilterWhere(['like', 'transactions.note', $this->note]);
        $query->orderBy(['id'=>SORT_DESC]);

        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'transactions.created_at',strtotime( $this->created_at_from)]);

        }

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'transactions.created_at',strtotime( $this->created_at_to)]);
        }

        // print_r($query->createCommand()->getRawSql());die;


        return $dataProvider;
    }
}
