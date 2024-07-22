<?php

namespace console\controllers;
use common\models\Order;
use common\models\Returns;
use common\models\ReturnsGroup;
use yii\console\Controller;

class CostsController extends Controller
{
    public function actionIndex()
    {
        $con = \Yii::$app->db;
        $Orders =  Order::find()->where('order.order_cost is  null')->all();
        foreach($Orders as $Order){
            $order_cost = null ;
            $products = $Order->products;
            foreach($products as $product){
                $product::updateAll(['items_cost'=>$product->product->price * $product->count],['id'=> $product->id]);
                $order_cost +=  $product->product->price * $product->count;
            }
           
            $Order::updateAll(['order_cost' => $order_cost],['id' => $Order->id]);
            print_r($Order->id);
            print PHP_EOL;
        }
    }
}

