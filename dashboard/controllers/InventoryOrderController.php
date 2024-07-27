<?php

namespace dashboard\controllers;

use common\components\CustomFunc;
use common\models\User;
use common\models\Supplier;
use kartik\mpdf\Pdf;
use Yii;
use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\InventoryOrderSearch;
use dashboard\components\BaseController;
use common\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * InventoryOrderController implements the CRUD actions for InventoryOrder model.
 */
class InventoryOrderController extends BaseController
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
     * Lists all InventoryOrder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InventoryOrderSearch();
        $params = $this->request->queryParams;
        if(!empty($params) && empty($params['InventoryOrderSearch']['created_at']))
        {
            $params['InventoryOrderSearch']['created_at_from'] = '';
        }


        // Check if search parameters are empty or if all parameters are empty
        $areParamsEmpty = empty($params) || array_filter($params['InventoryOrderSearch']) === [];
        if($areParamsEmpty)
        {
            $params['InventoryOrderSearch']['created_at_from'] =   CustomFunc::getFirstDayOfThisMonth();
        }
        // var_dump( $params);die;

        $dataProvider = $searchModel->search($params);
        if(!empty($searchModel->supplier_id))
        {
            $searchModel->supplierName = Supplier::findOne($searchModel->supplier_id)->name;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InventoryOrder model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->total_cost_without_discount = $model->getTotalCostWithoutDis();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new InventoryOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response|array
     */
    public function actionCreate()
    {
        $model = new InventoryOrder;
        $model_product = [new InventoryOrderProduct];
        if ($model->load(Yii::$app->request->post())) {

            $model_product = Model::createMultiple(InventoryOrderProduct::classname());
            Model::loadMultiple($model_product, Yii::$app->request->post());

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
                            $modelAddress->inventory_order_id = $model->id;
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
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new InventoryOrderProduct] : $model_product
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->total_cost_without_discount = $model->getTotalCostWithoutDis();
        $model_product = $model->products;
        if(!empty($model->supplier_id))
        {
            $model->supplierName = Supplier::findOne($model->supplier_id)->name;
        }
        
        if ($model->load(Yii::$app->request->post()) && !Yii::$app->user->can('تعديل السداد فقط في فاتورة المشتريات')) {
         

            $oldIDs = ArrayHelper::map($model_product, 'id', 'id');
            $model_product = Model::createMultiple(InventoryOrderProduct::classname(), $model_product);
            Model::loadMultiple($model_product, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($model_product, 'id', 'id')));

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
                            $productIDs =  InventoryOrderProduct::find()->select('product_id')->where(['id'=>$deletedIDs])->asArray()->all();
                            InventoryOrderProduct::deleteAll(['id' => $deletedIDs]);
                            foreach ($productIDs as $productID)
                            {
                                CustomFunc::calculateProductCount($model->store_id,$productID['product_id']);
                            }
                        }
                        foreach ($model_product as $modelproduct) {
                            $modelproduct->inventory_order_id = $model->id;
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
        }else{
            if(!empty(Yii::$app->request->post()) && Yii::$app->user->can('تعديل السداد فقط في فاتورة المشتريات') && $model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new InventoryOrderProduct] : $model_product
        ]);
    }


    /**
     * Deletes an existing InventoryOrder model.
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

    /**
     * Finds the InventoryOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return InventoryOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InventoryOrder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionAjaxProduct(){
        $model = new InventoryOrderProduct();
        return $this->renderAjax('_product',['model'=>$model]);
    }

    public function actionGetDetails($order_id)
    {
        $order = InventoryOrder::findOne($order_id);
        if ($order === null) {
            throw new \yii\web\NotFoundHttpException('The requested order does not exist.');
        }
        $supplier = $order->supplier;
        return $this->asJson([
            'supplier'=>[
                'id' => $order->supplier_id,
                'name' => $supplier ? $supplier->name : null,
                'phone_number' => $supplier ? $supplier->phone_number : null,
            ],
            'created_at' => $order->created_at,
            'created_by' => \common\components\CustomFunc::getUserName($order->created_by),
        ]);
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
                'SetHeader'=>['| {PAGENO}|'],
                'SetFooter'=>['| {PAGENO}|'],
            ],
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
}
