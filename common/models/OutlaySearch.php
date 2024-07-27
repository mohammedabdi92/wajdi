<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Outlay;

/**
 * OutlaySearch represents the model behind the search form of `common\models\Outlay`.
 */
class OutlaySearch extends Outlay
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
            [['id', 'created_by','store_id','user_id', 'updated_by'], 'integer'],
            [['amount'], 'number'],
            [['note', 'image_name','created_at','created_at_to','created_at_from', 'updated_at','updated_at_to','updated_at_from'], 'safe'],
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
        $query = Outlay::find();

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
        if(!\Yii::$app->user->can('كل المحلات') && empty($this->store_id))
        {
            $stores = \Yii::$app->user->identity->stores;
            $query->andWhere(['store_id'=>$stores]);
        }else{
            $query->andFilterWhere(['store_id'=>$this->store_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
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

        $query->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'image_name', $this->image_name]);
        $query->orderBy(['id'=>SORT_DESC]);

        return $dataProvider;
    }
}
