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
    public function actionIndex2()
    {
        $connection = \Yii::$app->db;
        $file = fopen(__DIR__.'/product2.csv', "r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

            $title= $data[0];
            $price = (float)$data[1] * (0.5);

            print $title.PHP_EOL;
            $product = new Product();
            $product->count_type = 1;
            $product->category_id = 6;
            $product->price = $price;
            $product->price_1 = round($price * (1.15), 3);
            $product->price_2 =  round($price * (1.25), 3);
            $product->price_3 = round($price * (1.35), 3);
            $product->price_4 = round($price * (1.45), 3);
            $product->title = $title;
            $product->created_at = time() ;
            $product->updated_at = time() ;
            $product->created_by = 2 ;
            $product->updated_by = 2 ;
            $product->status = 1 ;
            $product->save(false);


        }
    }
    public function actionIndex3()
    {
        $connection = \Yii::$app->db;
        $file = fopen(__DIR__.'/product4.csv', "r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

            $title= $data[0];
            $price = (float)$data[1] * (0.5);

            print $title.PHP_EOL;
            $product = new Product();
            $product->count_type = 1;
            $product->category_id = 6;
            $product->price = $price;
            $product->price_1 = round($price * (1.15), 3);
            $product->price_2 =  round($price * (1.25), 3);
            $product->price_3 = round($price * (1.35), 3);
            $product->price_4 = round($price * (1.45), 3);
            $product->title = $title;
            $product->created_at = time() ;
            $product->updated_at = time() ;
            $product->created_by = 2 ;
            $product->updated_by = 2 ;
            $product->status = 1 ;
            $product->save(false);


        }
    }
    public function actionIndex4()
    {
        $connection = \Yii::$app->db;
        $file = fopen(__DIR__.'/product5.csv', "r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

            $title= $data[0];
            $price = (float)$data[1] * (0.5);

            print $title.PHP_EOL;
            $product = new Product();
            $product->count_type = 1;
            $product->category_id = 6;
            $product->price = $price;
            $product->price_1 = round($price * (1.15), 3);
            $product->price_2 =  round($price * (1.25), 3);
            $product->price_3 = round($price * (1.35), 3);
            $product->price_4 = round($price * (1.45), 3);
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

