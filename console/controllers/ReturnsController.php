<?php

namespace console\controllers;
use common\models\Returns;
use common\models\ReturnsGroup;
use yii\console\Controller;

class ReturnsController extends Controller
{
    public function actionIndex()
    {
        $con = \Yii::$app->db;
        $returns =  Returns::find()->all();
        foreach($returns as $return){
            $con->createCommand()->insert(ReturnsGroup::tableName(), [
                'order_id' => $return->order_id,
                'note' => $return->note,
                'returner_name' => $return->returner_name,
                'created_at' => $return->created_at,
                'created_by' => $return->created_by,
                'updated_at' => $return->updated_at,
                'updated_by' => $return->updated_by,
                'total_amount' => $return->amount, 
                'total_count' =>$return->count
                ])
                        ->execute();
                $ReturnsGroupid = $con->getLastInsertID(ReturnsGroup::tableName());
                Returns::updateAll(['returns_group_id' => $ReturnsGroupid],['id' => $return->id]);
        }
    }
}

