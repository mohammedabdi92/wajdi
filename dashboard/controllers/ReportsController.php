<?php

namespace dashboard\controllers;

use common\models\InventorySearch;
use common\models\ProductSearch;
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
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}