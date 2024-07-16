<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\InventoryOrderProduct;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$InventoryOrderProduct = InventoryOrderProduct::find()->where(['product_id'=>$model->id])->orderBy(['id' => SORT_DESC])->one();
if($InventoryOrderProduct)
{
    $model->last_price =$InventoryOrderProduct->product_cost_final;
}
$InventoryOrderProductMin = InventoryOrderProduct::find()->where(['product_id'=>$model->id])->orderBy(['product_cost' => SORT_ASC])->one();
if($InventoryOrderProductMin)
{
    $model->min_price =$InventoryOrderProductMin->product_cost_final;

}
?>
<div class="product-view">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            [
                'attribute' => 'status',
                'value' => function($model){
                    return $model->getStatusText();
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'category_id',
                'value' => function($model){
                    return $model->categoryTitle;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'count_type',
                'value' => function($model){
                    return $model->getCountTypeName('count_type');
                },
                'format' => 'raw',
            ],
            
            'price',
            [
                'attribute' => 'price_1',
                'visible' => Yii::$app->user->can('سعر بيع 1'),
            ],
            [
                'attribute' => 'price_2',
                'visible' => Yii::$app->user->can('سعر بيع 2'),
            ],
            [
                'attribute' => 'price_3',
                'visible' => Yii::$app->user->can('سعر بيع 3'),
            ],
            [
                'attribute' => 'price_4',
                'visible' => Yii::$app->user->can('سعر بيع 4'),
            ],
            [
                'attribute'=>'image_name',
                'value'=>$model->getImageUrl(),
                'format' => ['image',['width'=>'100','height'=>'100']],
            ],
            [
                'attribute'=>'min_price',
                'visible'=>Yii::$app->user->can('اظهار اخر واقل سعر في عرض المواد'),
            ],
            [
                'attribute'=>'last_price',
                'visible'=>Yii::$app->user->can('اظهار اخر واقل سعر في عرض المواد'),
            ],
            [
                'attribute' => 'min_number',

                'format' => 'raw',
            ],
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
            ],
        ],
    ]) ?>

</div>
