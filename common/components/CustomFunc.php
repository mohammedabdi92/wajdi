<?php

namespace common\components;

use common\models\Inventory;
use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\OrderProduct;
use common\models\User;

class CustomFunc
{
    public static function getRandomString($length = 4 , $numberOnly = false) {
        $str = "";
        $characters = ( $numberOnly ) ? range('0','9') : array_merge(range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
    public static function getFullDate($date){
        return $date? date('Y-m-d H:i:s', $date):'';
    }
    public static function getUserName($id){
        $user = User::findOne($id);
        return  $user? $user->full_name:'';
    }
    public static function calculateProductCount($store_id,$product_id)
    {
        $item_inventory_count = InventoryOrderProduct::find()->where(['store_id'=>$store_id,'product_id'=>$product_id])->sum('count') ?? 0;
        $item_order_count = OrderProduct::find()->where(['store_id'=>$store_id,'product_id'=>$product_id])->sum('count')?? 0 ;
        $total = $item_inventory_count- $item_order_count;

            $inventory =  Inventory::find()->where(['store_id'=>$store_id,'product_id'=>$product_id])->one();
            if(empty($inventory))
            {
                $inventory = new Inventory();
                $inventory->product_id = $product_id;
                $inventory->store_id = $store_id;

            }

            if($inventory->count >= $inventory->product->min_number &&  $total < $inventory->product->min_number  ){
                \Yii::$app->mailer->compose()
                    ->setFrom(['info@mohammedabadi.com' => 'وجدي للاعمار'])
                    ->setTo("mohammed.abadi92@gmail.com")
                    ->setSubject("العنصر $product_id اصبح $total")
                    ->setHtmlBody("العنصر $product_id اصبح $total")
                    ->send();
            }
            $inventory->count = $total;

           return $inventory->save(false);



    }
}