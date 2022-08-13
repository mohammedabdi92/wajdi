<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InventorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'مواد الافرع');
$this->params['breadcrumbs'][] = $this->title;
$products = \common\models\Product::find()->all();
$productList = \yii\helpers\ArrayHelper::map($products, 'id', 'title');


?>
<div class="inventory-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

    <?php  echo $this->render('_search_sbah', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' =>function ($model){
            if(!empty($model->count) && $model->product && $model->count < $model->product->min_number ){
                return [
                    'class' => 'danger  time-set',
                    'data-text' => ' '
                ];
            }
            return ['class' => 'time-set-notSet'];


        },

        'columns' => [

            'id',
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    return $model->productTitle;
                },
                'format' => 'raw',
                'filter'=>$productList
            ],
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'filter'=>\common\components\Constants::storeArray,
                'format' => 'raw',
            ],
            'product.price_1',
            'product.price_2',
            'product.price_3',
            'product.price_4',
            'count',


        ],
    ]); ?>
    <div class="col-xs-6">
        <p class="lead">المجموع</p>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                <tr>
                    <th style="width:50%">السعر الاول :</th>
                    <td><?= $searchModel->sum_price_1?></td>
                </tr>
                <tr>
                    <th style="width:50%">السعر الثاني :</th>
                    <td><?= $searchModel->sum_price_2?></td>
                </tr>
                <tr>
                    <th style="width:50%">السعر الثالث :</th>
                    <td><?= $searchModel->sum_price_3?></td>
                </tr>
                <tr>
                    <th style="width:50%">السعر الرابع :</th>
                    <td><?= $searchModel->sum_price_4?></td>
                </tr>
                <tr>
                    <th style="width:50%">العدد :</th>
                    <td><?= $searchModel->sum_count?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
