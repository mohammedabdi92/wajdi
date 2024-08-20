<?php

namespace dashboard\controllers;

use common\components\CustomFunc;
use common\models\Damaged;
use common\models\Entries;
use common\models\FinancialWithdrawal;
use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\InventoryOrderProductSearch;
use common\models\InventorySearch;
use common\models\Maintenance;
use common\models\Order;
use common\models\OrderProductSearch;
use common\models\OrderSearch;
use common\models\Outlay;
use common\models\Presence;
use common\models\ProductSearch;
use common\models\Returns;
use common\models\Transactions;
use dashboard\components\BaseController;
use dashboard\models\cashBoxSearch;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ReportsController extends BaseController
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
        $params = $this->request->queryParams;
    
        $dataProvider = $searchModel->search($params,true);
        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionOrderProduct(){

        $searchModel = new OrderProductSearch();
        $params = $this->request->queryParams;
        if((!empty($params['OrderProductSearch']) && empty($params['OrderProductSearch']['created_at_from'])) || empty($params['OrderProductSearch'] )){
            $params['OrderProductSearch']['created_at_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }
        $dataProvider = $searchModel->search($params,true);

        return $this->render('order-product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionOrder(){

        $searchModel = new OrderSearch();
        $params = $this->request->queryParams;
        if((!empty($params['OrderSearch']) && empty($params['OrderSearch']['created_at_from'])) || empty($params['OrderSearch'] )){
            $params['OrderSearch']['created_at_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }

        $dataProvider = $searchModel->search($params,true);

        return $this->render('order', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInventoryOrderProduct()
    {

        $searchModel = new InventoryOrderProductSearch();
        $params = $this->request->queryParams;
        if((!empty($params['InventoryOrderProductSearch']) && empty($params['InventoryOrderProductSearch']['created_at_from'])) || empty($params['InventoryOrderProductSearch'] )){
            $params['InventoryOrderProductSearch']['created_at_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }

        $dataProvider = $searchModel->search($params,true);

        return $this->render('inventory-order-product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionPresence()
    {

        $searchModel = new Presence();
        $params = $this->request->queryParams;
        if((!empty($params['Presence']) && empty($params['Presence']['time_from'])) || empty($params['Presence'] )){
            $params['Presence']['time_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }


        $searchModel->load($params);
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
        $params = $this->request->queryParams;
        if((!empty($params['cashBoxSearch']) && empty($params['cashBoxSearch']['date_from'])) || empty($params['cashBoxSearch'] )){
            $params['cashBoxSearch']['date_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }


        $modelSearch->load($params);

        $productQuery = Order::find();
        $productQuery->joinWith('products.product');

        // + orders (damaged)
       
        $transactions_r_q = Transactions::find()->select('amount')->joinWith('order')->where(['type'=>Transactions::TYPE_REPAYMENT]);
        $maintenance_cost_q =  Maintenance::find()->select("maintenance_cost");
        $maintenance_paid_q =  Maintenance::find()->select("amount_paid");
        $order_q =  Order::find()->select("total_amount");
        $entries_q = Entries::find()->select("amount");
        $damaged_q =  Damaged::find()->select('amount')->joinWith('order');
        $damaged_q_p =  Damaged::find()->select('supplyer_price')->joinWith('order')->where([
            'and',
            ['is not','status_note_id', null],
            ['<>','status_note_id',  Damaged::STATUS_NOTE_NOT_RETURND],
        ]);
        $damaged_q_m = Damaged::find()->select('supplyer_pay_amount')->joinWith('order')->where(['status_note_id' => Damaged::STATUS_NOTE_RETURN_WITH_PAY]);
        $damaged_q_c = Damaged::find()->select('cost_value')->joinWith('order');



        // - inventory-order(dep) , outlay,returns,damaged
        $inventory_order_q =  InventoryOrder::find()->select('total_cost,debt,repayment');
        $inventory_order_repayment_q =  InventoryOrder::find()->select('repayment');
        $returns_q =  Returns::find()->select('amount')->joinWith('order');
        $outlay_q =  Outlay::find()->select('amount');
       
        $financial_withdrawal_q =  FinancialWithdrawal::find()->select('amount')->where(['status'=>FinancialWithdrawal::STATUS_NOT_PAYED]);

        if($modelSearch->date_from)
        {
            $productQuery->andWhere(['>=', 'order.created_at', strtotime( $modelSearch->date_from)]);
            $order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $transactions_r_q->andWhere(['>=', 'transactions.created_at', strtotime( $modelSearch->date_from)]);
            $entries_q->andWhere(['>=', 'put_date', $modelSearch->date_from]);
            $damaged_q->andWhere(['>=', 'damaged.created_at', strtotime( $modelSearch->date_from)]);
            $damaged_q_p->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $returns_q->andWhere(['>=', 'returns.created_at', strtotime( $modelSearch->date_from)]);
            $inventory_order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $inventory_order_repayment_q->andWhere(['>=', 'repayment_date', strtotime( $modelSearch->date_from)]);
            $outlay_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);
            $damaged_q_m->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $damaged_q_c->andWhere(['>=', 'damaged.cost_value_time', strtotime( $modelSearch->date_from)]);
            $financial_withdrawal_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);

            $maintenance_cost_q->andWhere(['>=', 'maintenance_cost_time', strtotime( $modelSearch->date_from)]);
            $maintenance_paid_q->andWhere(['>=', 'amount_paid_time', strtotime( $modelSearch->date_from)]);
        }

        if($modelSearch->date_to)
        {


            $productQuery->andWhere(['<=', 'order.created_at', strtotime( $modelSearch->date_to)]);
            $order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $transactions_r_q->andWhere(['<=', 'transactions.created_at', strtotime( $modelSearch->date_to)]);
            $entries_q->andWhere(['<=', 'put_date', $modelSearch->date_to]);
            $damaged_q->andWhere(['<=', 'damaged.created_at', strtotime( $modelSearch->date_to)]);
            $damaged_q_p->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $returns_q->andWhere(['<=', 'returns.created_at', strtotime( $modelSearch->date_to)]);
            $inventory_order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $inventory_order_repayment_q->andWhere(['<=', 'repayment_date', strtotime( $modelSearch->date_to)]);
            $outlay_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);
            $damaged_q_m->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $damaged_q_c->andWhere(['<=', 'damaged.cost_value_time', strtotime( $modelSearch->date_to)]);
            $financial_withdrawal_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);

            $maintenance_cost_q->andWhere(['<=', 'maintenance_cost_time', strtotime( $modelSearch->date_to)]);
            $maintenance_paid_q->andWhere(['<=', 'amount_paid_time', strtotime( $modelSearch->date_to)]);
        }
        if($modelSearch->store_id)
        {
            $productQuery->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $transactions_r_q->andWhere(['transactions.store_id'=>$modelSearch->store_id]);
            $entries_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $returns_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q_p->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $inventory_order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $inventory_order_repayment_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $outlay_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $damaged_q_m->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q_c->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $financial_withdrawal_q->andWhere(['store_id'=>$modelSearch->store_id]);

            $maintenance_cost_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $maintenance_paid_q->andWhere(['store_id'=>$modelSearch->store_id]);
        }


        $damaged_s_mince = $damaged_q_p->sum('supplyer_price');
        $damaged_s_p_mince = $damaged_q_m->sum('supplyer_pay_amount');
        
        // print_r($order_q->createCommand()->getRawSql());die;
        $damaged_mince = $damaged_q->sum('amount');
        // $damaged_mince += $damaged_q_m->sum('supplyer_pay_amount');
        $damaged_plus = $damaged_q_c->sum('cost_value');
        $outlay_mince = $outlay_q->sum('amount');
        $inventory_order_mince = $inventory_order_q->sum('total_cost - repayment');
        $maintenance_cost_mince = $maintenance_cost_q->sum('maintenance_cost');
        $maintenance_paid_pluse = $maintenance_paid_q->sum('amount_paid');
      
        $transactions_r_plus = $transactions_r_q->sum('amount');
        $returns_mince = $returns_q->sum('amount');
        $entries_pluse =  $entries_q->sum('amount');
        $order_pluse =  $order_q->sum('total_amount');
        $debt_sum =  $order_q->sum('debt');
        $inventory_debt = $inventory_order_q->sum('debt');
        $inventory_repayment = $inventory_order_repayment_q->sum('repayment');
        $financial_withdrawal_mince = $financial_withdrawal_q->sum('amount');

        $total_returns_amount = $productQuery->sum('(select sum(returns.amount) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_dept_returns_amount = $productQuery->sum('(select sum(returns.old_amount) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_profit_returns_amount  =    $total_dept_returns_amount - $total_returns_amount;

        $total_dept =  round($productQuery->sum('order_product.items_cost '),2);


        $box_in = (double)$order_pluse + (double)$entries_pluse + (double)$transactions_r_plus  + (double)$maintenance_paid_pluse + (double)$damaged_plus + (double) $damaged_s_mince ;
        $box_out =   (double)$inventory_order_mince + (double)$outlay_mince + (double)$damaged_mince + (double)$financial_withdrawal_mince+(double)$returns_mince+(double)$maintenance_cost_mince +(double)$inventory_repayment+$damaged_s_p_mince  ;


        $cash_amount =  $box_in - $box_out;
        $cash_amount = round($cash_amount, 2);
        $cash_amount_without_inventory_order = round( $cash_amount+$inventory_order_mince, 2);

        $total_profit  =  $order_pluse -  $total_dept + $total_profit_returns_amount + $debt_sum  ;
        $total_profit_without_damaged_outlay =  $total_profit -$damaged_mince -$outlay_mince - $debt_sum +$damaged_plus +(double) $damaged_s_mince  + ($maintenance_paid_pluse- $maintenance_cost_mince);

        return $this->render('cash-box', [
            'modelSearch'=>$modelSearch,
            'cash_amount'=>$cash_amount,
            'total_profit'=>round($total_profit,2),
            'total_profit_without_damaged_outlay'=>round($total_profit_without_damaged_outlay,2),
            'cash_amount_without_inventory_order'=>$cash_amount_without_inventory_order,
            'box_in'=> round($box_in, 2),
            'debt_sum'=> round($debt_sum, 2),
            'box_out'=> round($box_out, 2),
            'inventory_debt'=> round($inventory_debt, 2),
            'inventory_repayment'=> round($inventory_repayment, 2),
            'order_pluse'=>round($order_pluse, 2),
            'transactions_r_plus'=>round($transactions_r_plus, 2),
            'entries_pluse'=> round($entries_pluse, 2),
            'returns_mince'=> round($returns_mince, 2),
            'inventory_order_mince'=>round($inventory_order_mince, 2),
            'outlay_mince'=> round($outlay_mince, 2),
            'financial_withdrawal_mince'=> round($financial_withdrawal_mince, 2),
            'damaged_mince'=> round($damaged_mince, 2),
            'damaged_s_p_mince'=> round($damaged_s_p_mince, 2),
            'damaged_s_mince'=> round($damaged_s_mince, 2),
            'maintenance_cost_mince'=> round($maintenance_cost_mince, 2),
            'maintenance_paid_pluse'=> round($maintenance_paid_pluse, 2),
            'total_profit_returns_amount'=> round($total_profit_returns_amount, 2),
            'damaged_plus'=> round($damaged_plus, 2),
        ]);
    }


    public function actionUserCashBox()
    {
        $modelSearch = new cashBoxSearch();
        $params = $this->request->queryParams;
        if((!empty($params['cashBoxSearch']) && empty($params['cashBoxSearch']['date_from'])) || empty($params['cashBoxSearch'] )){
            $params['cashBoxSearch']['date_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }



        // + orders (damaged)
       
        $modelSearch->load($params);
        if(!\Yii::$app->user->can('كل المحلات') && empty($modelSearch->store_id))
        {
            $stores = \Yii::$app->user->identity->stores;
            $modelSearch->store_id  = $stores;
        }

        $productQuery = Order::find();
        $productQuery->joinWith('products.product');

        // + orders (damaged)
       
        $transactions_r_q = Transactions::find()->select('amount')->joinWith('order')->where(['type'=>Transactions::TYPE_REPAYMENT]);
        $maintenance_cost_q =  Maintenance::find()->select("maintenance_cost");
        $maintenance_paid_q =  Maintenance::find()->select("amount_paid");
        $order_q =  Order::find()->select("total_amount");
        $entries_q = Entries::find()->select("amount");
        $damaged_q =  Damaged::find()->select('amount')->joinWith('order');
        $damaged_q_p =  Damaged::find()->select('supplyer_price')->joinWith('order')->where([
            'and',
            ['is not','status_note_id', null],
            ['<>','status_note_id',  Damaged::STATUS_NOTE_NOT_RETURND],
        ]);
        $damaged_q_m = Damaged::find()->select('supplyer_pay_amount')->joinWith('order')->where(['status_note_id' => Damaged::STATUS_NOTE_RETURN_WITH_PAY]);
        $damaged_q_c = Damaged::find()->select('cost_value')->joinWith('order');



        // - inventory-order(dep) , outlay,returns,damaged
        $inventory_order_q =  InventoryOrder::find()->select('total_cost,debt');
        $inventory_order_repayment_q =  InventoryOrder::find()->select('repayment');
        $returns_q =  Returns::find()->select('amount')->joinWith('order');
        $outlay_q =  Outlay::find()->select('amount');
       
        $financial_withdrawal_q =  FinancialWithdrawal::find()->select('amount')->where(['status'=>FinancialWithdrawal::STATUS_NOT_PAYED]);

        if($modelSearch->date_from)
        {
            $productQuery->andWhere(['>=', 'order.created_at', strtotime( $modelSearch->date_from)]);
            $order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $transactions_r_q->andWhere(['>=', 'transactions.created_at', strtotime( $modelSearch->date_from)]);
            $entries_q->andWhere(['>=', 'put_date', $modelSearch->date_from]);
            $damaged_q->andWhere(['>=', 'damaged.created_at', strtotime( $modelSearch->date_from)]);
            $damaged_q_p->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $returns_q->andWhere(['>=', 'returns.created_at', strtotime( $modelSearch->date_from)]);
            $inventory_order_q->andWhere(['>=', 'created_at', strtotime( $modelSearch->date_from)]);
            $inventory_order_repayment_q->andWhere(['>=', 'repayment_date', strtotime( $modelSearch->date_from)]);
            $outlay_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);
            $damaged_q_m->andWhere(['>=', 'damaged.updated_at', strtotime( $modelSearch->date_from)]);
            $damaged_q_c->andWhere(['>=', 'damaged.cost_value_time', strtotime( $modelSearch->date_from)]);
            $financial_withdrawal_q->andWhere(['>=', 'pull_date',  $modelSearch->date_from]);

            $maintenance_cost_q->andWhere(['>=', 'maintenance_cost_time', strtotime( $modelSearch->date_from)]);
            $maintenance_paid_q->andWhere(['>=', 'amount_paid_time', strtotime( $modelSearch->date_from)]);
        }

        if($modelSearch->date_to)
        {


            $productQuery->andWhere(['<=', 'order.created_at', strtotime( $modelSearch->date_to)]);
            $order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $transactions_r_q->andWhere(['<=', 'transactions.created_at', strtotime( $modelSearch->date_to)]);
            $entries_q->andWhere(['<=', 'put_date', $modelSearch->date_to]);
            $damaged_q->andWhere(['<=', 'damaged.created_at', strtotime( $modelSearch->date_to)]);
            $damaged_q_p->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $returns_q->andWhere(['<=', 'returns.created_at', strtotime( $modelSearch->date_to)]);
            $inventory_order_q->andWhere(['<=', 'created_at', strtotime( $modelSearch->date_to)]);
            $inventory_order_repayment_q->andWhere(['<=', 'repayment_date', strtotime( $modelSearch->date_to)]);
            $outlay_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);
            $damaged_q_m->andWhere(['<=', 'damaged.updated_at', strtotime( $modelSearch->date_to)]);
            $damaged_q_c->andWhere(['<=', 'damaged.cost_value_time', strtotime( $modelSearch->date_to)]);
            $financial_withdrawal_q->andWhere(['<=', 'pull_date',  $modelSearch->date_to]);

            $maintenance_cost_q->andWhere(['<=', 'maintenance_cost_time', strtotime( $modelSearch->date_to)]);
            $maintenance_paid_q->andWhere(['<=', 'amount_paid_time', strtotime( $modelSearch->date_to)]);
        }
        if($modelSearch->store_id)
        {
            $productQuery->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $transactions_r_q->andWhere(['transactions.store_id'=>$modelSearch->store_id]);
            $entries_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $returns_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q_p->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $inventory_order_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $inventory_order_repayment_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $outlay_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $damaged_q_m->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $damaged_q_c->andWhere(['order.store_id'=>$modelSearch->store_id]);
            $financial_withdrawal_q->andWhere(['store_id'=>$modelSearch->store_id]);

            $maintenance_cost_q->andWhere(['store_id'=>$modelSearch->store_id]);
            $maintenance_paid_q->andWhere(['store_id'=>$modelSearch->store_id]);
        }


        $damaged_s_mince = $damaged_q_p->sum('supplyer_price');
        $damaged_s_p_mince = $damaged_q_m->sum('supplyer_pay_amount');
        
        // print_r($order_q->createCommand()->getRawSql());die;
        $damaged_mince = $damaged_q->sum('amount');
        // $damaged_mince += $damaged_q_m->sum('supplyer_pay_amount');
        $damaged_plus = $damaged_q_c->sum('cost_value');
        $outlay_mince = $outlay_q->sum('amount');
        $inventory_order_mince = $inventory_order_q->sum('total_cost');
        $maintenance_cost_mince = $maintenance_cost_q->sum('maintenance_cost');
        $maintenance_paid_pluse = $maintenance_paid_q->sum('amount_paid');
      
        $transactions_r_plus = $transactions_r_q->sum('amount');
        $returns_mince = $returns_q->sum('amount');
        $entries_pluse =  $entries_q->sum('amount');
        $order_pluse =  $order_q->sum('total_amount');
        $debt_sum =  $order_q->sum('debt');
        $inventory_debt = $inventory_order_q->sum('debt');
        $inventory_repayment = $inventory_order_repayment_q->sum('repayment');
        $financial_withdrawal_mince = $financial_withdrawal_q->sum('amount');

        $total_returns_amount = $productQuery->sum('(select sum(returns.amount) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_dept_returns_amount = $productQuery->sum('(select sum(returns.old_amount) from returns where returns.order_id = order.id and  order_product.product_id = returns.product_id)')  ;
        $total_profit_returns_amount  =    $total_dept_returns_amount - $total_returns_amount;

        $total_dept =  round($productQuery->sum('order_product.items_cost '),2);


        $box_in = (double)$order_pluse + (double)$entries_pluse + (double)$transactions_r_plus  + (double)$maintenance_paid_pluse + (double)$damaged_plus + (double) $damaged_s_mince ;
        $box_out =   (double)$inventory_order_mince + (double)$outlay_mince + (double)$damaged_mince + (double)$financial_withdrawal_mince+(double)$returns_mince+(double)$maintenance_cost_mince +(double)$inventory_repayment+$damaged_s_p_mince  ;


        $cash_amount =  $box_in - $box_out;
        $cash_amount = round($cash_amount, 2);
        $cash_amount_without_inventory_order = round( $cash_amount+$inventory_order_mince, 2);

        $total_profit  =  $order_pluse -  $total_dept + $total_profit_returns_amount + $debt_sum  ;
        $total_profit_without_damaged_outlay =  $total_profit -$damaged_mince -$outlay_mince - $debt_sum +$damaged_plus +(double) $damaged_s_mince  + ($maintenance_paid_pluse- $maintenance_cost_mince);

        return $this->render('user-cash-box', [
            'modelSearch'=>$modelSearch,
            'cash_amount'=>$cash_amount,
            'total_profit'=>round($total_profit,2),
            'total_profit_without_damaged_outlay'=>round($total_profit_without_damaged_outlay,2),
            'cash_amount_without_inventory_order'=>$cash_amount_without_inventory_order,
            'box_in'=> round($box_in, 2),
            'debt_sum'=> round($debt_sum, 2),
            'box_out'=> round($box_out, 2),
            'inventory_debt'=> round($inventory_debt, 2),
            'inventory_repayment'=> round($inventory_repayment, 2),
            'order_pluse'=>round($order_pluse, 2),
            'transactions_r_plus'=>round($transactions_r_plus, 2),
            'entries_pluse'=> round($entries_pluse, 2),
            'returns_mince'=> round($returns_mince, 2),
            'inventory_order_mince'=>round($inventory_order_mince, 2),
            'outlay_mince'=> round($outlay_mince, 2),
            'financial_withdrawal_mince'=> round($financial_withdrawal_mince, 2),
            'damaged_mince'=> round($damaged_mince, 2),
            'damaged_s_p_mince'=> round($damaged_s_p_mince, 2),
            'damaged_s_mince'=> round($damaged_s_mince, 2),
            'maintenance_cost_mince'=> round($maintenance_cost_mince, 2),
            'maintenance_paid_pluse'=> round($maintenance_paid_pluse, 2),
            'total_profit_returns_amount'=> round($total_profit_returns_amount, 2),
            'damaged_plus'=> round($damaged_plus, 2),
        ]);
       
    }

}