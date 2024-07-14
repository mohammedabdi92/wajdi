<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Maintenance;

/**
 * MaintenanceSearch represents the model behind the search form of `common\models\Maintenance`.
 */
class MaintenanceSearch extends Maintenance
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'client_id', 'item_count', 'service_center_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['client_note', 'status', 'maintenance_note'], 'safe'],
            [['amount_paid', 'maintenance_cost', 'cost_difference'], 'number'],
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
        $query = Maintenance::find();

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
            'client_id' => $this->client_id,
            'item_count' => $this->item_count,
            'amount_paid' => $this->amount_paid,
            'service_center_id' => $this->service_center_id,
            'maintenance_cost' => $this->maintenance_cost,
            'cost_difference' => $this->cost_difference,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'client_note', $this->client_note])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'maintenance_note', $this->maintenance_note]);

        return $dataProvider;
    }
}
