<?php

namespace dashboard\controllers;

use common\models\InventoryOrderProduct;
use common\models\InventoryOrderProductSearch;
use common\models\InventorySearch;
use common\models\OrderProductSearch;
use common\models\Presence;
use common\models\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ReportsController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }



    public function actionProducts(){

        $searchModel = new InventorySearch();
        $dataProvider = $searchModel->search($this->request->queryParams,true);

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionOrderProduct(){

        $searchModel = new OrderProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams,true);

        return $this->render('order-product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInventoryOrderProduct()
    {

        $searchModel = new InventoryOrderProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, true);

        return $this->render('inventory-order-product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPresence()
    {

        $searchModel = new Presence();
        $searchModel->load($this->request->queryParams);
        $query =  Presence::find()->select([
            '*',
            'time_out'=>"@time_outs :=(SELECT p2.time FROM presence as p2 where p2.type = 2 AND p2.time >= presence.time AND `time` LIKE CONCAT('%' ,CONCAT( DATE(presence.time) , '%'))  LIMIT 1)",
            'diff_time_out_mints'=>'TIMESTAMPDIFF(MINUTE,`time`,@time_outs)'
            ])->where(['type'=>Presence::TYPE_IN]);




        if($searchModel->user_id)
        {
            $query->andWhere(['user_id'=>$searchModel->user_id]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        return $this->render('presence', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}