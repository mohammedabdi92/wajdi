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

        $query =  Presence::find()->where(['type'=>Presence::TYPE_IN])->with('outPresence');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        print_r("<pre>");
        print_r($dataProvider->getModels());die;



        $searchModel = new InventoryOrderProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, true);

        return $this->render('inventory-order-product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}