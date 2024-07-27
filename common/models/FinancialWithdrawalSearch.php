<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FinancialWithdrawal;

/**
 * FinancialWithdrawalSearch represents the model behind the search form of `common\models\FinancialWithdrawal`.
 */
class FinancialWithdrawalSearch extends FinancialWithdrawal
{
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
            [['id', 'status','user_id', 'created_by','store_id',  'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['note','created_at','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from'], 'safe'],
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
        $query = FinancialWithdrawal::find();

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
            'amount' => $this->amount,
            'status' => $this->status,
            'store_id' => $this->store_id,
            'user_id' => $this->user_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);
        if($this->created_at_from)
        {
            $query->andFilterWhere(['>=', 'created_at',strtotime( $this->created_at_from)]);

        }
       

        if($this->created_at_to)
        {
            $query->andFilterWhere(['<=', 'created_at',strtotime( $this->created_at_to)]);
        }
        if($this->updated_at_from)
        {
            $query->andFilterWhere(['>=', 'updated_at',strtotime( $this->updated_at_from)]);

        }

        if($this->updated_at_to)
        {
            $query->andFilterWhere(['<=', 'updated_at',strtotime( $this->updated_at_to)]);
        }


        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
