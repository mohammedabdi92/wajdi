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
                    'confirm' => Yii::t('app', 'هل انت متاكد من الحذف ؟'),
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
            [
                'attribute' => 'customer_id',
                'value' => function ($model) {
                    return $model->customer->name ?? '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'store_id',
                'value' => function($model){
                    return $model->storeTitle;
                },
                'format' => 'raw',
            ],
            'total_amount_without_discount',
            [
                'attribute' => 'total_discount',
                'value' => function($model){
                    return $model->total_discount+$model->total_price_discount_product;
                },
                'format' => 'raw',
            ],
            'total_amount',
            'note',
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
                echo "<br><div>اسم المادة :    $item->productTitle </div>";
                echo DetailView::widget([
                    'model' => $item,
                    'attributes' => [
                        'count',
                        [
                            'label' => 'نوع العد',
                            'value' => function ($model) {
                                return $model->productCountType;
                            },
                        ],
                        'amount',
                        'total_product_amount',

                    ],
                ]);
            }


            ?>
        </div>

        <div class="col-md-6">
            <?php
            echo '<label>المرجع</label>';
            echo \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([ 'query' => \common\models\Returns::find()->where(['order_id'=>$model->id])]),
                'columns' => [
                    'id',
                    'count',
                    [
                        'attribute' => 'product_name',
                        'value' => function($model){
                            return $model->productTitle;
                        },
                        'label'=>"المادة",
                        'format' => 'raw',

                    ],
                    'returner_name',
                    [
                        'attribute' => 'created_at',
                        'value' => function($model){
                            return \common\components\CustomFunc::getFullDate($model->created_at);
                        },
                    ],
                ],
            ]);
            echo '<label>مجموع :</label>'.\common\models\Returns::find()->where(['order_id'=>$model->id])->count('amount');

            echo '<label>التالف</label>';
            echo \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([ 'query' => \common\models\Damaged::find()->where(['order_id'=>$model->id])]),
                'columns' => [
                    'id',
                    'count',
                    [
                        'attribute' => 'product_name',
                        'value' => function($model){
                            return $model->productTitle;
                        },
                        'label'=>"المادة",
                        'format' => 'raw',

                    ],
                ],
            ]);
            ?>
        </div>
    </div>

</div>
