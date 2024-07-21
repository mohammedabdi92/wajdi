<?php

namespace dashboard\controllers;

use common\models\ServiceCenter;
use common\models\ServiceCenterSearch;
use dashboard\components\BaseController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServiceCenterController implements the CRUD actions for ServiceCenter model.
 */
class ServiceCenterController extends BaseController
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
     * Lists all ServiceCenter models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ServiceCenterSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceCenter model.
     * @param int $id الرقم
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
     * Creates a new ServiceCenter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ServiceCenter();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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
     * Updates an existing ServiceCenter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id الرقم
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ServiceCenter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id الرقم
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
   

    public function actionList($q = null, $id = null,$store_id=null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = ServiceCenter::find();
           
            $query->select('service_center.id, name AS text')
                ->from('service_center')
                ->limit(20);
            $parts = preg_split('/\s+/', $q);
            foreach ($parts as $part){
                $query->andWhere(['like', 'LOWER( service_center.name )', "$part"]);
            }
            $data = $query->asArray()->all();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ServiceCenter::find($id)->name];
        }
        return $out;
    }

    /**
     * Finds the ServiceCenter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id الرقم
     * @return ServiceCenter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceCenter::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
