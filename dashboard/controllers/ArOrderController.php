<?php

namespace dashboard\controllers;

use common\models\ArOrder;
use common\models\Product;
use kartik\mpdf\Pdf;
use Yii;
use common\base\Model;
use common\models\Order;
use common\models\ArOrderProduct;
use common\models\ArOrderSearch;
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
class ArOrderController extends BaseController
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
        $searchModel = new ArOrderSearch();
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



    public function actionUpdate($id)
    {
        $post = \Yii::$app->request->post();
        $is_draft = false;
        if(isset($post['draft']))
        {
            $is_draft = true;
        }


        $model = $this->findModel($id);
        $model_product = $model->products;
        if ($model->load($post)) {


            $oldIDs = ArrayHelper::map($model_product, 'id', 'id');
            $model_product = Model::createMultiple(ArOrderProduct::classname(), $model_product);
            Model::loadMultiple($model_product, $post);
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
                            ArOrderProduct::deleteAll(['id' => $deletedIDs]);
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
                        if(!$is_draft)
                        {
                            $nModel =  $model->cloneModel("common\models\Order");
                            $nModel->save(false);
                            foreach ($model_product as $modelproduct) {
                                $nModelproduct =  $modelproduct->cloneModel("common\models\OrderProduct");
                                $nModelproduct->order_id = $nModel->id;
                                $nModelproduct->store_id = $nModel->store_id;
                                $nModelproduct->save(false);
                            }


                        }
                        return $this->redirect(['order/view', 'id' => $nModel->id]);
                    }
                } catch (\Exception $e) {

                    print_r($e->getMessage());die;
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new ArOrderProduct] : $model_product
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
                echo '<label><input type="radio" name="ArOrderProduct['.$key.'][price_number]" value="'.$value.'"> '.$price.'</label>';
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
        if (($model = ArOrder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionOrderProducts()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $order_id = $parents[0];

                $OrderProducts = ArOrderProduct::find()->where(['order_id'=>$order_id])->all();

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
        $order = ArOrder::findOne($order_id);
        $orderProduct = ArOrderProduct::find()->where(['order_id'=>$order_id,'product_id'=>$product_id])->one();

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
}
