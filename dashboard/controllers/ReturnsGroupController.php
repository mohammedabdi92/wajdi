<?php

namespace dashboard\controllers;

use Yii;
use common\models\ReturnsGroup;
use common\models\Returns;
use common\base\Model;
use common\models\ReturnsGroupSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ReturnsGroupController implements the CRUD actions for ReturnsGroup model.
 */
class ReturnsGroupController extends Controller
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
     * Lists all ReturnsGroup models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ReturnsGroupSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ReturnsGroup model.
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
     * Creates a new ReturnsGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $post = \Yii::$app->request->post();
        $model = new ReturnsGroup();
        $model_product = [new Returns()];
        if ($model->load($post)) {

            $model_product = Model::createMultiple(Returns::classname());
            Model::loadMultiple($model_product, $post);

            foreach ($model_product as $item) {
                $item->order_id = $model->order_id;
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
                                $modelAddress->returns_group_id = $model->id;
                                $modelAddress->order_id = $model->order_id;
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
            'model_product' => (empty($model_product)) ? [new Returns] : $model_product
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model_product = $model->returns;

        if ($model->load(Yii::$app->request->post())) {


            // print_r(Yii::$app->request->post());die;
            $oldIDs = ArrayHelper::map($model_product, 'id', 'id');
            $model_product = Model::createMultiple(Returns::classname(), $model_product);
            Model::loadMultiple($model_product, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($model_product, 'id', 'id')));

            foreach ($model_product as $item) {
                $item->order_id = $model->order_id;
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
                            Returns::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($model_product as $modelproduct) {
                            $modelproduct->returns_group_id = $model->id;
                            $modelproduct->order_id = $model->order_id;

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
     * Deletes an existing ReturnsGroup model.
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
     * Finds the ReturnsGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ReturnsGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ReturnsGroup::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
