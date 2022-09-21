<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TransferOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'النقليات');
$this->params['breadcrumbs'][] = $this->title;
$products = \common\models\Product::find()->all();
$productList = \yii\helpers\ArrayHelper::map($products, 'id', 'title');

?>
<div class="transfer-order-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء طلب نقل'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'product_id',
                'value' => function($model) {
                    return $model->productTitle;
                },
                'format' => 'raw',
                'filter'=>$productList
            ],
            [
                'attribute' => 'from',
                'value' => function($model){
                    return $model->fromTitle;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name'),
                'format' => 'raw',
            ],
            [
                'attribute' => 'to',
                'value' => function($model){
                    return $model->toTitle;
                },
                'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name'),
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
            ],


        ],
    ]); ?>


</div>
