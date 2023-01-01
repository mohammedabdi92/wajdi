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


?>
<div class="inventory-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
    </p>

    <?php  echo $this->render('_search_sbah', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
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
                'format' => 'raw'
            ],
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'),
                'format' => 'raw',
            ],
            'product.price',
            'product.price_1',
            'product.price_2',
            'product.price_3',
            'product.price_4',
            'count',
        ],
    ]); ?>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <p class="lead">المجموع</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:50%">سعر الكلفة :</th>
                        <td><?= round($searchModel->sum_price, 2)?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">السعر الاول :</th>
                        <td><?= round($searchModel->sum_price_1,2)  ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">ربح السعر الاول :</th>
                        <td><?= round($searchModel->sum_price_1 - $searchModel->sum_price,2) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">السعر الثاني :</th>
                        <td><?= round($searchModel->sum_price_2,2) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">ربح السعر الثاني :</th>
                        <td><?= round($searchModel->sum_price_2 - $searchModel->sum_price,2) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">السعر الثالث :</th>
                        <td><?= round($searchModel->sum_price_3,2) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">ربح السعر الثالث :</th>
                        <td><?= round($searchModel->sum_price_3 - $searchModel->sum_price,2) ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">السعر الرابع :</th>
                        <td><?= round($searchModel->sum_price_4,2)  ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">ربح السعر الرابع :</th>
                        <td><?= round($searchModel->sum_price_4 - $searchModel->sum_price ,2) ?></td>
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


</div>
