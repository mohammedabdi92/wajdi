<?php

namespace common\components;

use common\models\Damaged;
use common\models\Inventory;
use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\Order;
use common\models\OrderProduct;
use common\models\Returns;
use common\models\Store;
use common\models\TransferOrder;
use common\models\User;
use function Symfony\Component\String\s;

class CustomFunc
{
    public static function getRandomString($length = 4, $numberOnly = false)
    {
        $str = "";
        $characters = ($numberOnly) ? range('0', '9') : array_merge(range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public static function getFullDate($date)
    {
        return $date ? date('Y-m-d H:i:s', $date) : '';
    }

    public static function getUserName($id)
    {
        $user = User::findOne($id);
        return $user ? $user->full_name : '';
    }

    public static function calculateProductCount($store_id, $product_id, $old_product_id = null)
    {

        $item_inventory_count = InventoryOrderProduct::find()->select('count')->where(['store_id' => $store_id, 'product_id' => $product_id])->sum('count') ?? 0;
        $item_order_count = OrderProduct::find()->select('count')->where(['store_id' => $store_id, 'product_id' => $product_id])->sum('count') ?? 0;
        $returned = Returns::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id])->sum('count');
        $damaged_returned = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id, 'status' => Damaged::STATUS_RETURNED])->sum('count');
        $damaged_inactive = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id])->andWhere(['<>', 'status', Damaged::STATUS_REPLACED])->sum('count');
        $transformTo = TransferOrder::find()->select('count')->where(['to' => $store_id, 'product_id' => $product_id])->sum('count');
        $transformFrom = TransferOrder::find()->select('count')->where(['from' => $store_id, 'product_id' => $product_id])->sum('count');

        $total = (double)$item_inventory_count + (double)$returned - (double)$item_order_count + (double)$damaged_returned - (double)$damaged_inactive - (double)$transformFrom + (double)$transformTo;


        $inventory = Inventory::find()->where(['store_id' => $store_id, 'product_id' => $product_id])->one();
        if (empty($inventory)) {
            $inventory = new Inventory();
            $inventory->product_id = $product_id;
            $inventory->store_id = $store_id;
        }

        if ($inventory->count >= $inventory->product->min_number && $total < $inventory->product->min_number) {
            \Yii::$app->mailer->compose()
                ->setFrom(['info@mohammedabadi.com' => 'وجدي للاعمار'])
                ->setTo("mohammed.abadi92@gmail.com")
                ->setSubject("العنصر $product_id اصبح $total")
                ->setHtmlBody("العنصر $product_id اصبح $total")
                ->send();
        }
        $inventory->count = ($total < 0) ? 0 : $total;

        $inventory->save(false);

        if ($old_product_id) {

            $item_inventory_count = InventoryOrderProduct::find()->select('count')->where(['store_id' => $store_id, 'product_id' => $product_id])->sum('count') ?? 0;
            $item_order_count = OrderProduct::find()->select('count')->where(['store_id' => $store_id, 'product_id' => $product_id])->sum('count') ?? 0;
            $returned = Returns::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id])->sum('count');
            $damaged_returned = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id, 'status' => Damaged::STATUS_RETURNED])->sum('count');
            $damaged_inactive = Damaged::find()->select('count')->joinWith('order')->where(['order.store_id' => $store_id, 'product_id' => $product_id])->andWhere(['<>', 'status', Damaged::STATUS_REPLACED])->sum('count');
            $transformTo = TransferOrder::find()->select('count')->where(['to' => $store_id, 'product_id' => $product_id])->sum('count');
            $transformFrom = TransferOrder::find()->select('count')->where(['from' => $store_id, 'product_id' => $product_id])->sum('count');

            $total = (int)$item_inventory_count + (int)$returned - (int)$item_order_count + (int)$damaged_returned - (int)$damaged_inactive - (int)$transformFrom + (int)$transformTo;

            $inventory = Inventory::find()->where(['store_id' => $store_id, 'product_id' => $product_id])->one();
            if (empty($inventory)) {
                $inventory = new Inventory();
                $inventory->product_id = $product_id;
                $inventory->store_id = $store_id;
            }

            if ($inventory->count >= $inventory->product->min_number && $total < $inventory->product->min_number) {
                \Yii::$app->mailer->compose()
                    ->setFrom(['info@mohammedabadi.com' => 'وجدي للاعمار'])
                    ->setTo("mohammed.abadi92@gmail.com")
                    ->setSubject("العنصر $product_id اصبح $total")
                    ->setHtmlBody("العنصر $product_id اصبح $total")
                    ->send();
            }
            $inventory->count = ($total < 0) ? 0 : $total;
            $inventory->save(false);
        }


        return true;
    }

    public static function removeOrderProduct($product_id, $order_id, $count)
    {
        $orderProduct = OrderProduct::find()->where(['order_id' => $order_id, 'product_id' => $product_id])->one();
        if ($orderProduct) {
            if ($orderProduct->count == $count) {
                $orderProduct->delete();
                $orderHaveProduct = OrderProduct::find()->where(['order_id' => $order_id])->one();
                if (!$orderHaveProduct) {
                    Order::deleteAll(['id' => $order_id]);
                }
            } else {
                $orderProduct->count = $orderProduct->count - $count;
            }
            self::calculateOrderProduct($order_id);
        }
    }

    public static function calculateOrderProduct($order_id)
    {

    }
}