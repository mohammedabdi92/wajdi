<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReturnsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المرجع');
$this->params['breadcrumbs'][] = $this->title;
$products = \common\models\Product::find()->where(['status'=>1])->all();
$productList = \yii\helpers\ArrayHelper::map($products, 'id', 'title');

?>
<div class="returns-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء مرجع'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_id',
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    return $model->productTitle;
                },
                'format' => 'raw',
                'filter'=>$productList
            ],
            'count',
            'amount',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, \common\models\Returns $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
        ],
    ]); ?>


</div>
