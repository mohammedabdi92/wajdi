<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrder */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="inventory-order-view">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'العودة'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'supplier_id',
                'value' => function ($model) {
                    return $model->supplierTitle;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'store_id',
                'value' => function ($model) {
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            'total_cost',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->created_by);
                },
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getFullDate($model->created_at);
                },
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    return \common\components\CustomFunc::getUserName($model->updated_by);
                },
            ],
        ],
    ]);
    $items = $model->products;
    foreach ($items as $item) {
        echo "<div>اسم السلعة :    $item->productTitle </div>";
        echo DetailView::widget([
            'model' => $item,
            'attributes' => [
                'count',
                'product_cost',
                'product_total_cost',

            ],
        ]);
    }

    ?>


</div>
