<?php

namespace dashboard\controllers;

use common\models\Damaged;
use common\models\Entries;
use common\models\FinancialWithdrawal;
use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\InventoryOrderProductSearch;
use common\models\InventorySearch;
use common\models\Order;
use common\models\OrderProductSearch;
use common\models\Outlay;
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
        if($searchModel->time_from)
        {
            $query->andWhere(['>=', 'time', $searchModel->time_from]);
        }
        if($searchModel->time_to)
        {
            $query->andWhere(['<=', 'time', $searchModel->time_to]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
//        print_r($query->createCommand()->rawSql);die;

        $searchModel->total_diff_time_out_mints = $query->sum("(TIMESTAMPDIFF(MINUTE,`time`,(SELECT p2.time FROM presence as p2 where p2.type = 2 AND p2.time >= presence.time AND `time` LIKE CONCAT('%' ,CONCAT( DATE(presence.time) , '%'))  LIMIT 1))  )");
        if(!empty($searchModel->total_diff_time_out_mints))
        {
            $mins =$searchModel->total_diff_time_out_mints;
            $hours= floor($mins/(60));
            $mints_last =   $mins - floor($mins/(60));
            $searchModel->total_diff_time_out_mints=$hours.':'.$mints_last;
        }


        return $this->render('presence', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCashBox()
    {

        // + orders (damaged)
        $order_q =  Order::find()->select("total_amount");
        $entries_q = Entries::find()->select("amount");

        $entries_pluse =  $entries_q->sum('amount');
        $order_pluse =  $order_q->sum('total_amount');

        $damaged_q =  Damaged::find()->select('amount')->where(['status'=>Damaged::STATUS_RETURNED]);
        $damaged_plus = $damaged_q->sum('amount');

        // - inventory-order(dep) , outlay,returns,damaged
        $inventory_order_q =  InventoryOrder::find()->select('total_cost');
        $inventory_order_mince = $inventory_order_q->sum('total_cost');

        $outlay_q =  Outlay::find()->select('amount');
        $outlay_mince = $outlay_q->sum('amount');

        $damaged_q_m =  Damaged::find()->select('amount')->where(['status'=>Damaged::STATUS_INACTIVE]);
        $damaged_mince = $damaged_q_m->sum('amount');

        $financial_withdrawal_q =  FinancialWithdrawal::find()->select('amount')->where(['status'=>FinancialWithdrawal::STATUS_NOT_PAYED]);
        $financial_withdrawal_mince = $financial_withdrawal_q->sum('amount');

        $box_in = (double)$order_pluse + (double)$entries_pluse + (double)$damaged_plus;
        $box_out =   (double)$inventory_order_mince + (double)$outlay_mince + (double)$damaged_mince + (double)$financial_withdrawal_mince;


        $cash_amount =  $box_in - $box_out;
        $cash_amount = round($cash_amount, 2);

        print_r($box_in.'------');
        print_r($box_out.'-----');
        print_r($cash_amount);die;



        return $this->render('cash-box', [
        ]);
    }

}