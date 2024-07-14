<?php

namespace dashboard\controllers;

use common\models\ArOrder;
use common\models\ArOrderProduct;
use common\models\Customer;
use common\models\FkOrder;
use common\models\InventoryOrder;
use common\models\Product;
use common\models\Transactions;
use kartik\mpdf\Pdf;
use Yii;
use common\base\Model;
use common\models\Order;
use common\models\OrderProduct;
use common\models\OrderSearch;
use dashboard\components\BaseController;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseController
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

    /**
     * Lists all Order models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $params = $this->request->queryParams;
        
        $dataProvider = $searchModel->search($params);
        if(!empty($searchModel->customer_id))
        {
            $searchModel->customerName = Customer::findOne($searchModel->customer_id)->name;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response|array
     */
    public function actionCreate()
    {
        $is_draft = false;
        $post = \Yii::$app->request->post();
        if(isset($post['draft']))
        {
            $is_draft = true;

        }
        if(isset($post['Order']))
        {
            $post['ArOrder'] = $post['Order'];
        }
        if(isset($post['OrderProduct']))
        {
            $post['ArOrderProduct'] = $post['OrderProduct'];
        }


        $model = new Order;
        $model_product = [new OrderProduct()];
        if ($model->load($post)) {

            $model_product = Model::createMultiple(OrderProduct::classname());
            Model::loadMultiple($model_product, $post);

            foreach ($model_product as $item) {
                $item->store_id = $model->store_id;
            }
            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_product),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($model_product) && $valid;



            if ($valid) {
                if (!$is_draft)
                {
                    $transaction = \Yii::$app->db->beginTransaction();

                    try {
                        if ($flag = $model->save()) {

                            if($model->debt)
                            {
                                $dept_model  =  Transactions::find()->where(['order_id'=>$model->id])->one();
                                if(!$dept_model){
                                    $dept_model = new Transactions();
                                }
                                $dept_model->type = Transactions::TYPE_DEBT;
                                $dept_model->order_id = $model->id;
                                $dept_model->amount = $model->debt;
                                $dept_model->customer_id = $model->customer_id;
                                $dept_model->note = $model->dept_note;
                                $dept_model->save(false);

                            }

                            foreach ($model_product as $modelAddress) {
                                $modelAddress->order_id = $model->id;
                                $modelAddress->store_id = $model->store_id;

                                if (! ($flag = $modelAddress->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (\Exception $e) {
                        die($e);
                        $transaction->rollBack();
                    }
                }else{


                    $model = new ArOrder;
                    $model->load($post);
                    Yii::$app->request->setBodyParams($post);
                    $model_product = Model::createMultiple(ArOrderProduct::classname());

                    Model::loadMultiple($model_product, $post);


                    foreach ($model_product  as $item) {
                        $item->store_id = $model->store_id;
                    }

                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        $model->debt = null;
                        $model->dept_note = null;

                        if ($flag = $model->save(false)) {
                            foreach ($model_product as $modelAddress) {
                                $modelAddress->order_id = $model->id;
                                $modelAddress->store_id = $model->store_id;

                                if (! ($flag = $modelAddress->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['ar-order/view', 'id' => $model->id]);
                        }
                    } catch (\Exception $e) {
                        die($e);
                        $transaction->rollBack();
                    }

                }

            }
        }
        if(!empty($model->customer_id))
        {
            $model->customerName = Customer::findOne($model->customer_id)->name;
        }

        return $this->render('create', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new OrderProduct] : $model_product
        ]);
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_product = $model->products;
        if(!empty($model->customer_id))
        {
            $model->customerName = Customer::findOne($model->customer_id)->name;
        }

        if ($model->load(Yii::$app->request->post())) {


            $oldIDs = ArrayHelper::map($model_product, 'id', 'id');
            $model_product = Model::createMultiple(OrderProduct::classname(), $model_product);
            Model::loadMultiple($model_product, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($model_product, 'id', 'id')));

            foreach ($model_product as $item) {
                $item->store_id = $model->store_id;
            }
            // ajax validation
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($model_product),
                    ActiveForm::validate($model)
                );
            }

            // validate all models
            $valid = $model->validate();

            $valid = Model::validateMultiple($model_product) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    if ($flag = $model->save(false)) {
                        $dept_model  =  Transactions::find()->where(['order_id'=>$model->id])->one();
                        if($model->debt)
                        {

                            if(!$dept_model){
                                $dept_model = new Transactions();
                            }
                            $dept_model->type = Transactions::TYPE_DEBT;
                            $dept_model->order_id = $model->id;
                            $dept_model->amount = $model->debt;
                            $dept_model->customer_id = $model->customer_id;
                            $dept_model->note = $model->dept_note;
                            $dept_model->save(false);

                        }elseif($dept_model)
                        {
                            $dept_model->delete();
                        }
                        if (! empty($deletedIDs)) {
                            OrderProduct::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($model_product as $modelproduct) {
                            $modelproduct->order_id = $model->id;
                            $modelproduct->store_id = $model->store_id;

                            if (! ($flag = $modelproduct->save())) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {

                    print_r($e->getMessage());die;
                    $transaction->rollBack();
                }
            }
        }

        $Customer_REPAYMENT =  Transactions::find()->where(['customer_id'=>$model->customer_id , 'type'=>Transactions::TYPE_REPAYMENT])->sum('amount');
        $Customer_DEBT =  Transactions::find()->where(['customer_id'=>$model->customer_id , 'type'=>Transactions::TYPE_DEBT])->sum('amount');

        return $this->render('update', [
            'model' => $model,
            'dept_data' =>$Customer_REPAYMENT? ['customer_id'=>$model->customer_id ,'repayment_amount'=>$Customer_REPAYMENT,'debt_amount'=> $Customer_DEBT ,'customer_name'=>$model->customer->name]:null,
            'model_product' => (empty($model_product)) ? [new OrderProduct] : $model_product
        ]);
    }


    public function actionGetCustomer($id)
    {
        $Customer =   Customer::findOne($id);
        if(!$Customer)
        {
            return '';
        }
        $Customer_REPAYMENT =  Transactions::find()->where(['customer_id'=>$id , 'type'=>Transactions::TYPE_REPAYMENT])->sum('amount');
        $Customer_DEBT =  Transactions::find()->where(['customer_id'=>$id , 'type'=>Transactions::TYPE_DEBT])->sum('amount');

        return $this->renderPartial("_customer_dept_history",["dept_data"=>$Customer_REPAYMENT? ['customer_id'=>$id ,'repayment_amount'=>$Customer_REPAYMENT,'debt_amount'=> $Customer_DEBT ,'customer_name'=>$Customer->name]:null]);
    }


    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionPriceList($key,$id)
    {
        $key = explode('-',$key)[1];
        $product = Product::findOne($id);

        if (!empty($product)) {
            $prices = $product->getPriceList();
            foreach($prices as $value=>$price) {
                echo '<label><input type="radio" name="OrderProduct['.$key.'][price_number]" value="'.$value.'"> '.$price.'</label>';
            }
        }
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionReport($id) {

        $model = $this->findModel($id);
        $products = $model->products;

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('pdf',['model'=>$model,'products'=>$products]);




        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => [
                'title' => 'فاتورة بيع '.$model->id,
                'subject' => 'فاتورة بيع '.$model->id,
                'default_font' => 'cairo',
            ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['| {PAGENO} |'],
                'SetFooter'=>['| {PAGENO} |'],
            ],


        ]);
        $pdf->options = array_merge($pdf->options , [
            'fontDir' =>  [ Yii::$app->basePath.'/../dashboard/web/fonts/'],  // make sure you refer the right physical path
            'fontdata' =>  [
                'cairo' => [
                    'R' => 'cairo-v4-arabic_latin-regular.ttf',
                    'I' => 'cairo-v4-arabic_latin-700.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ]
            ],
            'default_font' => 'cairo',
        ]);
        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    public function actionOrderProducts()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $order_id = $parents[0];

                $OrderProducts = OrderProduct::find()->where(['order_id'=>$order_id])->all();

                $dataArray = array();
                $count = 0;
                foreach ($OrderProducts as $model){
                    $dataArray[$count]['id'] = $model->product_id;
                    $dataArray[$count]['name'] = $model->product->title;
                    $count ++;
                }


                return ['output' => $dataArray, 'selected' => [''=>'اختر ...']];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetProductPrice($order_id,$product_id,$count)
    {
        $order = Order::findOne($order_id);
        $orderProduct = OrderProduct::find()->where(['order_id'=>$order_id,'product_id'=>$product_id])->one();

        if($order && $order->total_discount)
        {
            $percentage_discount =  $order->total_discount / $order->total_amount_without_discount;
            $product_price  = (1-$percentage_discount ) * $orderProduct->amount  ;

        }else{
            $product_price =  !empty($orderProduct->discount)?($orderProduct->amount - ($orderProduct->discount/$orderProduct->count)):$orderProduct->amount;
        }


        $product_price_for_count =  $product_price * $count;

        return  round($product_price_for_count, 2) ;
    }
    public function actionGetDetails($order_id)
    {
        $order = Order::findOne($order_id);
        if ($order === null) {
            throw new \yii\web\NotFoundHttpException('The requested order does not exist.');
        }
        $customer = $order->customer;
        return $this->asJson([
            'customer'=>[
                'id' => $order->customer_id,
                'name' => $customer ? $customer->name : null,
                'phone_number' => $customer ? $customer->phone_number : null,
            ],
            'created_at' => $order->created_at,
            'created_by' => \common\components\CustomFunc::getUserName($order->created_by),
        ]);
    }
    public function actionCreateFk(){

        $addingFiveMinutes= strtotime('- 15 minute');
        $orders = Order::find()->andWhere(['>=', 'created_at', $addingFiveMinutes])->asArray()->all();

        foreach ($orders as $order)
        {
            $order_id = $order['id'];
             unset($order['id']);
            $order['clone_by'] =  Yii::$app->user->identity->id;
            $order['clone_at'] =  time();
            Yii::$app->db->createCommand()->insert('fk_order', $order)->execute();
            $fk_order_id = \Yii::$app->db->getLastInsertID('fk_order');
            $products = OrderProduct::find()->andWhere(['order_id'=>$order_id])->asArray()->all();
            foreach ($products as $product)
            {
                unset($product['id']);
                $product['order_id'] = $fk_order_id;
                $product['clone_by'] =  Yii::$app->user->identity->id;
                $product['clone_at'] =  time();
                Yii::$app->db->createCommand()->insert('fk_order_product', $product)->execute();
            }

        }
        $InventoryOrders = InventoryOrder::find()->andWhere(['>=', 'created_at', $addingFiveMinutes])->asArray()->all();
        foreach ($InventoryOrders as $order)
        {
            $order_id = $order['id'];
            unset($order['id']);
            $order['clone_by'] =  Yii::$app->user->identity->id;
            $order['clone_at'] =  time();
            Yii::$app->db->createCommand()->insert('fk_inventory_order', $order)->execute();
            $fk_order_id = \Yii::$app->db->getLastInsertID('fk_inventory_order');
            $products = OrderProduct::find()->andWhere(['inventory_order_id'=>$order_id])->asArray()->all();
            foreach ($products as $product)
            {
                unset($product['id']);
                $product['inventory_order_id'] = $fk_order_id;
                $product['clone_by'] =  Yii::$app->user->identity->id;
                $product['clone_at'] =  time();

                Yii::$app->db->createCommand()->insert('fk_inventory_order_product', $product)->execute();
            }

        }
        $url = Url::to(['fk-order/index']);
        $script = <<<JS
            function openInNewTab(url) {
              var win = window.open(url, '_blank','location=yes'+",width="+screen.availWidth+",height="+screen.availHeight);
              win.focus();
            }
             openInNewTab('$url');
JS;
        $this->getView()->registerJs( $script , \yii\web\View::POS_READY );
        return $this->render ( 'external' );
    }
    public  function actionIsDept($id)
    {
        $order = Order::findOne($id);
        if($order && $order->debt)
        {
            return "هذا الطلب يحتوي على دين بقيمة ".$order->debt ;
        }
        return false;
    }
}
