<?php

namespace dashboard\controllers;

use common\components\CustomFunc;
use common\models\User;
use kartik\mpdf\Pdf;
use Yii;
use common\models\FkInventoryOrder;
use common\models\FkInventoryOrderProduct;
use common\models\FkInventoryOrderSearch;
use dashboard\components\BaseController;
use common\base\Model;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * FkInventoryOrderController implements the CRUD actions for FkInventoryOrder model.
 */
class FkInventoryOrderController extends BaseController
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
        $this->layout = "main-fk";
        $searchModel = new FkInventoryOrderSearch();
        $params = $this->request->queryParams;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FkInventoryOrder model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = "main-fk";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FkInventoryOrder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response|array
     */
    public function actionCreate()
    {
        $this->layout = "main-fk";
        $model = new FkInventoryOrder;
        $model_product = [new FkInventoryOrderProduct];
        if ($model->load(Yii::$app->request->post())) {

            $model_product = Model::createMultiple(FkInventoryOrderProduct::classname());
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
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'model_product' => (empty($model_product)) ? [new FkInventoryOrderProduct] : $model_product
        ]);
    }

    public function actionUpdate($id)
    {
        $this->layout = "main-fk";
        $model = $this->findModel($id);
        $model_product = $model->products;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($model_product, 'id', 'id');
            $model_product = Model::createMultiple(FkInventoryOrderProduct::classname(), $model_product);
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
                            $productIDs =  FkInventoryOrderProduct::find()->select('product_id')->where(['id'=>$deletedIDs])->asArray()->all();
                            FkInventoryOrderProduct::deleteAll(['id' => $deletedIDs]);
                            foreach ($productIDs as $productID)
                            {
                                CustomFunc::calculateProductCount($model->store_id,$productID['product_id']);
                            }
                        }
                        foreach ($model_product as $modelproduct) {
                            $modelproduct->inventory_order_id = $model->id;
                            $modelproduct->store_id = $model->store_id;
                            if (! ($flag = $modelproduct->save(false))) {
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
            'model_product' => (empty($model_product)) ? [new FkInventoryOrderProduct] : $model_product
        ]);
    }


    /**
     * Deletes an existing FkInventoryOrder model.
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
     * Finds the FkInventoryOrder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return FkInventoryOrder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FkInventoryOrder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionAjaxProduct(){
        $model = new FkInventoryOrderProduct();
        return $this->renderAjax('_product',['model'=>$model]);
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
