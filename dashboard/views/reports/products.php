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
            'count',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },
            ],
            [
                'attribute' => 'created_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return \common\components\CustomFunc::getFullDate($model->updated_at);
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function($model){
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
            ],

        ],
    ]); ?>


</div>
