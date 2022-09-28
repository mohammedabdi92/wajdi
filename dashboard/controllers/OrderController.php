<?php

namespace dashboard\controllers;

use common\models\Product;
use kartik\mpdf\Pdf;
use Yii;
use common\base\Model;
use common\models\Order;
use common\models\OrderProduct;
use common\models\OrderSearch;
use dashboard\components\BaseController;
use yii\helpers\ArrayHelper;
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
        $model = new Order;
        $model_product = [new OrderProduct()];
        if ($model->load(\Yii::$app->request->post())) {

            $model_product = Model::createMultiple(OrderProduct::classname());
            Model::loadMultiple($model_product, \Yii::$app->request->post());

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
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (\Exception $e) {
                    die($e);
                    $transaction->rollBack();
                }
            }
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

        return $this->render('update', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new OrderProduct] : $model_product
        ]);
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


                return ['output' => $dataArray, 'selected' => ''];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionGetProductPrice($order_id,$product_id,$count)
    {
        $orderProduct = OrderProduct::find()->where(['order_id'=>$order_id,'product_id'=>$product_id])->one();

        $product_price =  !empty($orderProduct->discount)?($orderProduct->amount - ($orderProduct->discount/$orderProduct->count)):$orderProduct->amount;
        $product_price_for_count =  $product_price * $count;

        return $product_price_for_count;
    }
}
