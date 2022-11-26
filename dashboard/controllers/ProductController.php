<?php

namespace dashboard\controllers;

use common\models\InventoryOrder;
use common\models\InventoryOrderProduct;
use common\models\Product;
use common\models\ProductSearch;
use dashboard\components\BaseController;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends BaseController
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
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ( $model->save()) {
                $model->upload();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($this->request->isPost)
        {
            $model->load($this->request->post());
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ( $model->save()) {
                $model->upload();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }



        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->softDelete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionGetDetails($id){
        $response = ['last_price'=>'','min_price'=>''];
        $Product = $this->findModel($id);
        $response['product_price']  = $Product->price;
        $InventoryOrderProduct = InventoryOrderProduct::find()->where(['product_id'=>$id])->orderBy(['id' => SORT_DESC])->one();
        if($InventoryOrderProduct)
        {
            $response['last_price'] =$InventoryOrderProduct->product_cost;
        }
        $InventoryOrderProductMin = InventoryOrderProduct::find()->where(['product_id'=>$id])->orderBy(['product_cost' => SORT_ASC])->one();
        if($InventoryOrderProductMin)
        {
            $response['min_price'] =$InventoryOrderProductMin->product_cost;
        }
        return json_encode($response);
    }

    public function actionProductList($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = Product::find();
            $query->select('id, title AS text')
                ->from('product')
                ->limit(20);
            $parts = preg_split('/\s+/', $q);
            foreach ($parts as $part){
                $query->andWhere(['like', 'LOWER( product.title )', "$part"]);
            }
            $data = $query->asArray()->all();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Product::find($id)->name];
        }
        return $out;
    }
    public function actionGetDetials($id){
       return json_encode(Product::findOne($id)->getAttributes());
    }
}
