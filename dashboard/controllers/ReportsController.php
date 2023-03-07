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
use common\models\OrderSearch;
use common\models\Outlay;
use common\models\Presence;
use common\models\ProductSearch;
use common\models\Returns;
use dashboard\models\cashBoxSearch;
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
    public function actionOrder(){

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams,true);

        return $this->render('order', [
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
            'time_out'=>"@time_outs :=(SELECT p2.time FROM presence as p2 where p2.type = 2 AND presence.user_id = p2.user_id  AND p2.time >= presence.time AND `time` LIKE CONCAT('%' ,CONCAT( DATE(presence.time) , '%'))  LIMIT 1)",
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

        $searchModel->total_diff_time_out_mints = $query->sum("(TIMESTAMPDIFF(MINUTE,`time`,(SELECT p2.time FROM presence as p2 where p2.type = 2  AND presence.user_id = p2.user_id AND p2.time >= presence.time AND `time` LIKE CONCAT('%' ,CONCAT( DATE(presence.time) , '%'))  LIMIT 1))  )");
        if(!empty($searchModel->total_diff_time_out_mints))
        {
            $mins =$searchModel->total_diff_time_out_mints;
            $hours= floor($mins/(60));
            $mints_last =   fmod($mins, 60);
            $searchModel->total_diff_time_out_mints=$hours.':'.$mints_last;
        }
        $query->orderBy(['id'=>SORT_DESC]);

        return $this->render('presence', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCashBox()
    {
        $modelSearch = new cashBoxSearch();
        $modelSearch->load($this->request->queryParams);

        $productQuery = Order::find();
        $productQuery->joinWith('products.product');

        // + orders (damaged)
        $order_q =  Order::find()->select("total_amount");
        $entries_q = Entries::find()->select("amount");
        $damaged_q =  Damaged::find()->select('amount')->joinWith('order')->where(['status'=>Damaged::STATUS_RETURNED]);


        // - inventory-order(dep) , outlay,returns,damaged
        $inventory_order_q =  InventoryOrder::find()->select('total_cost');
        $returns_q =  Returns::find()->select('amount')->joinWith('order');
        $outlay_q =  Outlay::find()->select('amount');
        $damaged_q_m =  Damaged::find()->select('amount')->joinWith('order')->where(['status'=>Damaged::STATUS_INACTIVE]);
        $financial_withdrawal_q =  FinancialWithdrawal::find()->select('amount')->where(['status'=>FinancialWithdrawal::STATUS_NOT_PAYED]);

        if($modelSearch->date_from)
        {
            $productQuery->andWhere(['>=', 'order.created_at', strtotime( $modelSearch->date_from)]);
            $order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $entries_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $damaged_q->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $returns_q->andWhere(['>=', 'returns.created_at', strtotime( $modelSearch->date_from)]);
            $inventory_order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $outlay_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);
            $damaged_q_m->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $financial_withdrawal_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);
        }

        if($modelSearch->date_to)
        {

            $modelSearch->date_to .= " 23:59:59";

            $productQuery->andWhere(['<=', 'order.created_at', strtotime( $modelSearch->date_to)]);
            $order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $entries_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $damaged_q->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $returns_q->andWhere(['<=', 'returns.created_at', strtotime( $modelSearch->date_to)]);
            $inventory_order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $outlay_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);
            $damaged_q_m->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $financial_withdrawal_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);
        }
        if($modelSearch->store_id)
        {
            $productQuery->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $entries_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $returns_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $inventory_order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $outlay_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $damaged_q_m->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $financial_withdrawal_q->andWhere(['store_id'=>$modelSearch->store_id]);
        }



        $damaged_mince = $damaged_q_m->sum('amount');
        $outlay_mince = $outlay_q->sum('amount');
        $inventory_order_mince = $inventory_order_q->sum('total_cost');
        $damaged_plus = $damaged_q->sum('amount');
        $returns_mince = $returns_q->sum('amount');
        $entries_pluse =  $entries_q->sum('amount');
        $order_pluse =  $order_q->sum('total_amount');
        $order_q->andWhere('order.debt is  null');
        $orderw_pluse =  $order_q->sum('total_amount');

        $financial_withdrawal_mince = $financial_withdrawal_q->sum('amount');

        $total_returns_amount = $productQuery->sum('(select sum(returns.amount) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_dept_returns_amount = $productQuery->sum('(select sum(returns.count * product.price) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_profit_returns_amount  =  $total_returns_amount  - $total_dept_returns_amount ;

        $productQuery->andWhere('order.debt is  null');
        $total_dept =  round($productQuery->sum('(product.price * order_product.count) '),2);


        $box_in = (double)$order_pluse + (double)$entries_pluse + (double)$damaged_plus;
        $box_out =   (double)$inventory_order_mince + (double)$outlay_mince + (double)$damaged_mince + (double)$financial_withdrawal_mince+(double)$returns_mince;


        $cash_amount =  $box_in - $box_out;
        $cash_amount = round($cash_amount, 2);
        $cash_amount_without_inventory_order = round( $cash_amount+$inventory_order_mince, 2);

        $total_profit  =  $orderw_pluse -  $total_dept - $total_profit_returns_amount ;
        $total_profit_without_damaged_outlay =  $total_profit -$damaged_mince -$outlay_mince;

        return $this->render('cash-box', [
            'modelSearch'=>$modelSearch,
            'cash_amount'=>$cash_amount,
            'total_profit'=>round($total_profit,2),
            'total_profit_without_damaged_outlay'=>round($total_profit_without_damaged_outlay,2),
            'cash_amount_without_inventory_order'=>$cash_amount_without_inventory_order,
            'box_in'=> round($box_in, 2),
            'box_out'=> round($box_out, 2),
            'order_pluse'=>round($order_pluse, 2),
            'entries_pluse'=> round($entries_pluse, 2),
            'returns_mince'=> round($returns_mince, 2),
            'damaged_plus'=> round($damaged_plus, 2),
            'inventory_order_mince'=>round($inventory_order_mince, 2),
            'outlay_mince'=> round($outlay_mince, 2),
            'financial_withdrawal_mince'=> round($financial_withdrawal_mince, 2),
            'damaged_mince'=> round($damaged_mince, 2),
        ]);
    }

}