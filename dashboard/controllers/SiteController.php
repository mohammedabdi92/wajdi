<?php

namespace dashboard\controllers;

use common\models\InventoryOrder;
use common\models\LoginForm;
use common\models\Order;
use common\models\OrderProduct;
use common\models\Outlay;
use common\models\Presence;
use common\models\Returns;
use common\models\Transactions;
use common\models\User;
use dashboard\components\BaseController;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }



    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $currentMonth = date('m');
        $currentYear = date('Y');

        // Fetch order amounts for each day of the month
        $orderData = Yii::$app->db->createCommand("
       SELECT DAY(FROM_UNIXTIME(created_at)) AS day, SUM(total_amount) AS total_amount1
        FROM `order`
        WHERE MONTH(FROM_UNIXTIME(created_at)) = MONTH(CURDATE())
        AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(CURDATE())
        GROUP BY DAY(FROM_UNIXTIME(created_at))
        ORDER BY day;


        
         ")->queryAll();

        // Fetch inventory order amounts for each day of the month
        $inventoryData = Yii::$app->db->createCommand("
        SELECT DAY(FROM_UNIXTIME(created_at)) AS day, SUM(total_cost) AS total_cost1
        FROM inventory_order
        WHERE MONTH(FROM_UNIXTIME(created_at)) = MONTH(CURDATE())
        AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(CURDATE())
        GROUP BY DAY(FROM_UNIXTIME(created_at))
        ORDER BY day;
    ")->queryAll();

        $outlaysData = Yii::$app->db->createCommand("
        SELECT DAY(FROM_UNIXTIME(created_at)) AS day, SUM(amount) AS amount
        FROM `outlays`
        WHERE MONTH(FROM_UNIXTIME(created_at)) = MONTH(CURDATE())
        AND YEAR(FROM_UNIXTIME(created_at)) = YEAR(CURDATE())
        GROUP BY DAY(FROM_UNIXTIME(created_at))
        ORDER BY day;
    ")->queryAll();

        // Convert data to a suitable format for chart
        $days = range(1, date('t')); // Days of the month
        $orderAmounts = array_fill(0, count($days), 0);
        $inventoryAmounts = array_fill(0, count($days), 0);
        $outlaysAmounts = array_fill(0, count($days), 0);

        foreach ($orderData as $data) {
            $orderAmounts[$data['day'] - 1] = $data['total_amount1'];
        }

        foreach ($inventoryData as $data) {
            $inventoryAmounts[$data['day'] - 1] = $data['total_cost1'];
        }
        foreach ($outlaysData as $data) {
            $outlaysAmounts[$data['day'] - 1] = $data['amount'];
        }
        $specificDay = date('Y-m-d'); // Replace with the day you're interested in

        $totalDiscount = Order::find()
            ->select(['SUM(total_discount) AS total_discount'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
        $returnsAmount = Returns::find()->select(['SUM(amount) AS amount'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
        $orderAmount = Order::find()
            ->select(['SUM(total_amount) AS total_amount'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
            
        $inventoryOrderAmount = InventoryOrder::find()
            ->select(['SUM(total_cost) AS total_cost'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
        $debtAmount = Order::find()
            ->select(['SUM(debt) AS debt'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
        $repaymentAmount = Transactions::find()
            ->select(['SUM(amount) AS amount'])
            ->where(['DATE(FROM_UNIXTIME(created_at))' => $specificDay,'type'=>Transactions::TYPE_REPAYMENT])
            ->scalar(); // Use scalar() to get the single value
        $outlayAmount = Outlay::find()
            ->select(['SUM(amount) AS amount'])
            ->where(['DATE(pull_date)' => $specificDay])
            ->scalar(); // Use scalar() to get the single value
          
       

        return $this->render('index', [
            'days' => $days,
            'orderAmounts' => $orderAmounts,
            'inventoryAmounts' => $inventoryAmounts,
            'outlaysAmounts' => $outlaysAmounts,
            "totalDiscount" => $totalDiscount,
            "returnsAmount" => $returnsAmount,
            "orderAmount" => $orderAmount,
            "inventoryOrderAmount" => $inventoryOrderAmount,
            "debtAmount" => $debtAmount,
            "repaymentAmount" => $repaymentAmount,
            "outlayAmount" => $outlayAmount,
        ]);

        // return $this->render('index');
    }



    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = false;

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionSend()
    {
        $send = Yii::$app->mailer->compose()
            ->setFrom('info@wajdi.top')
            ->setTo('qusayjamilsham@gmail.com')
            ->setSubject('قوك')
            ->setTextBody('وصلك')
            ->send();
        var_dump($send);
        die;
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
