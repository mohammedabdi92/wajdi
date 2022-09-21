<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'البيع');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>



    <?php  echo $this->render('_search_order_product', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'order_id',
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    return $model->productTitle;
                },
            ],
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
            ],
            [
                'attribute' => 'product.price',
                'label'=>'سعر الكلفة',
            ],
            'count',
            'amount',
            'total_product_amount',
            'discount',
            [
                'label'=>'الاجمالي بعد الخصم',
                'value' => function($model){
                    return $model->total_product_amount - $model->discount ;
                },
            ],



        ],
    ]); ?>


</div>

<div class="row">
    <div class="col-xs-6">
        <p class="lead">المجموع</p>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th style="width:50%">السعر الكلفة :</th>
                    <td><?= $searchModel->sum_product_price?></td>
                </tr>
                <tr>
                    <th style="width:50%">العدد :</th>
                    <td><?= $searchModel->sum_count?></td>
                </tr>
                <tr>
                    <th style="width:50%">السعر الاجمالي :</th>
                    <td><?= $searchModel->sum_total_product_amount?></td>
                </tr>
                <tr>
                    <th style="width:50%">الخصم :</th>
                    <td><?= $searchModel->sum_discount?></td>
                </tr>
                <tr>
                    <th style="width:50%">الاجمالي بعد الخصم :</th>
                    <td><?= $searchModel->sum_total_amount_w_discount?></td>
                </tr>
                <tr>
                    <th style="width:50%">الاجمالي الربح :</th>
                    <td><?= $searchModel->sum_profit?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
