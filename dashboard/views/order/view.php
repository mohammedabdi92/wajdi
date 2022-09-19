<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'رجوع'), ['index'], ['class' => 'btn btn-primary']) ?>
        <?php
        if(Yii::$app->user->can('تعديل وحذف فواتير المبيعات'))
        {
            echo Html::a(Yii::t('app', 'تعديل'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'customer_id',
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            'total_amount',
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
    <div class="row">
        <div class="col-md-6">
            <label>المباع</label>
            <?php
            $items = $model->products;
            foreach ($items as $item) {
                echo "<div>اسم المادة :    $item->productTitle </div>";
                echo DetailView::widget([
                    'model' => $item,
                    'attributes' => [
                        'count',
                        'amount',
                        'total_product_amount',

                    ],
                ]);
            }

            ?>
        </div>
        <div class="col-md-6">
            <label>المرجع</label>

        </div>

    </div>
    <div class="col-md-6">
        <label>التالف</label>

    </div>
</div>
