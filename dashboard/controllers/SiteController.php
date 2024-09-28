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



        $m = date('m');
        $y = date('Y');
        $dt = strtotime("$y-$m-01 00:00:00");

        // Fetch order amounts for each day of the month
        $orderData = Yii::$app->db->createCommand("
        SELECT DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00')) AS day, SUM(total_amount) AS total_amount1
        FROM `order`
        WHERE created_at >= $dt
        GROUP BY DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00'))
        ORDER BY day;
         ")->queryAll();

        // Fetch inventory order amounts for each day of the month
        $inventoryData = Yii::$app->db->createCommand("
        SELECT DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00')) AS day, SUM(total_cost) AS total_cost1
        FROM inventory_order
        WHERE created_at >= $dt
        GROUP BY DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00'))
        ORDER BY day;
        ")->queryAll();

        $outlaysData = Yii::$app->db->createCommand("
        SELECT DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00')) AS day, SUM(amount) AS amount
        FROM `outlays`
        WHERE created_at >= $dt
        GROUP BY DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00'))
        ORDER BY day;
        ")->queryAll();


         $debtData = Yii::$app->db->createCommand("
            SELECT 
                DAY(CONVERT_TZ(FROM_UNIXTIME(o.created_at), '+00:00', '+03:00')) AS day, 
                SUM(t.amount) AS amount
            FROM 
                `transactions` t
            JOIN 
                `order` o ON t.order_id = o.id  
            WHERE 
                o.created_at >= $dt AND t.type = 1
            GROUP BY 
                DAY(CONVERT_TZ(FROM_UNIXTIME(o.created_at), '+00:00', '+03:00'))
            ORDER BY 
                day;
         ")->queryAll();
         $repaymentData = Yii::$app->db->createCommand("
         SELECT DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00')) AS day, SUM(amount) AS amount
         FROM `transactions`
         WHERE created_at >= $dt and type = 2
         GROUP BY DAY(CONVERT_TZ(FROM_UNIXTIME(created_at), '+00:00', '+03:00'))
         ORDER BY day;
          ")->queryAll();

       

        // Convert data to a suitable format for chart
        $days = range(1, date('t')); // Days of the month
        $orderAmounts = array_fill(0, count($days), 0);
        $inventoryAmounts = array_fill(0, count($days), 0);
        $outlaysAmounts = array_fill(0, count($days), 0);
        $debtsAmounts = array_fill(0, count($days), 0);
        $repaymentsAmounts = array_fill(0, count($days), 0);
        foreach ($debtData as $data) {
            $debtsAmounts[$data['day'] - 1] = $data['amount'];
        }

        foreach ($repaymentData as $data) {
            $repaymentsAmounts[$data['day'] - 1] = $data['amount'];
        }


        foreach ($orderData as $data) {
            $orderAmounts[$data['day'] - 1] = $data['total_amount1'];
        }

        foreach ($inventoryData as $data) {
            $inventoryAmounts[$data['day'] - 1] = $data['total_cost1'];
        }
        foreach ($outlaysData as $data) {
            $outlaysAmounts[$data['day'] - 1] = $data['amount'];
        }
        $specificDay = date('Y-m-d 00:00:00'); // Replace with the day you're interested in

        $totalDiscount = Order::find()
            ->select(['SUM(total_discount) AS total_discount'])
            ->where(['>=', 'created_at', strtotime($specificDay)])
            ->scalar(); // Use scalar() to get the single value
        $returnsAmount = Returns::find()->select(['SUM(amount) AS amount'])
            ->where(['>=', 'created_at', strtotime($specificDay)])
            ->scalar(); // Use scalar() to get the single value
        $orderAmount = Order::find()
            ->select(['SUM(total_amount) AS total_amount'])
            ->where(['>=', 'created_at', strtotime($specificDay)])
            ->scalar(); // Use scalar() to get the single value

        $inventoryOrderAmount = InventoryOrder::find()
            ->select(['SUM(total_cost) AS total_cost'])
            ->where(['>=', 'created_at', strtotime($specificDay)])
            ->scalar(); // Use scalar() to get the single value
        $debtAmount = Order::find()
            ->select(['SUM(debt) AS debt'])
            ->where(['>=', 'created_at', strtotime($specificDay)])
            ->scalar(); // Use scalar() to get the single value
        $repaymentAmount = Transactions::find()
            ->select(['SUM(amount) AS amount'])
            ->where(['>=', 'created_at', strtotime($specificDay)])->andWhere(['type' => Transactions::TYPE_REPAYMENT])
            ->scalar(); // Use scalar() to get the single value
        $outlayAmount = Outlay::find()
            ->select(['SUM(amount) AS amount'])
            ->where(['>=', 'pull_date', $specificDay])
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
            "debtsAmounts" => $debtsAmounts,
            "repaymentsAmounts" => $repaymentsAmounts,
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
    public function actionLoginWithoutPassword()
    {
        // Find user with ID 1 (or any other ID you want)
        $user = User::findOne(1);

        // Check if the user exists
        if ($user !== null) {
            // Log the user in without password
            Yii::$app->user->login($user);

            // Redirect to home page after login
            return $this->redirect(['site/index']);
        }

        // If the user doesn't exist, show a 404 error
        throw new \yii\web\NotFoundHttpException('User not found.');
    }
}
