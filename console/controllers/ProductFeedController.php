<?php

namespace console\controllers;
use common\models\Product;
use yii\console\Controller;

class ProductFeedController extends Controller
{
    public function actionIndex()
    {
        $connection = \Yii::$app->db;
        $file = fopen(__DIR__.'/product1.csv', "r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

            $title= $data[0];
            $count_type = $data[1];
            $price = (float)$data[2];

            print $title.PHP_EOL;
            $product = new Product();
            $product->count_type = $count_type;
            $product->category_id = 8;
            $product->price = $price;
            $product->price_1 = $price * (1.2);
            $product->price_2 = $price * (1.4);
            $product->price_3 = $price * (1.6);
            $product->price_4 = $price * (1.8);
            $product->title = $title;
            $product->created_at = time() ;
            $product->updated_at = time() ;
            $product->created_by = 2 ;
            $product->updated_by = 2 ;
            $product->status = 1 ;
            $product->save(false);


        }
    }
}

