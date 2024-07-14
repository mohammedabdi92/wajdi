<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InventoryOrderProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المشتريات');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-order-product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>


    <?php  echo $this->render('_search_inventory_order_product', ['model' => $searchModel]); ?>


    <?php

    $gridColumns=[


        'inventory_order_id',
        [
            'attribute' => 'store_id',
            'value' => function($model){

                return $model->storeTitle??'';
            },

        ],
        [
            'attribute' => 'inventoryOrder.supplier_id',
            'value' => function ($model) {

                return $model->inventoryOrder->supplierTitle ?? '';
            },

        ],


        [
            'attribute' => 'product_id',
            'value' => function($model){
                return $model->productTitle;
            },

        ],
        'count',
        'product_cost_final',
        'product_total_cost_final',
        [
            'attribute' => 'created_at',
            'value' => function($model){
                return \common\components\CustomFunc::getFullDate($model->created_at);
            },
        ],


    ];
    if(Yii::$app->user->can('صلاحية الطباعة'))
    {
        ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'target' => ExportMenu::TARGET_BLANK,
        ]);
        echo ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'target' => ExportMenu::TARGET_BLANK,
            'fontAwesome' => true,
            'pjaxContainerId' => 'kv-pjax-container',
            'batchSize' => 40,
            'filename' => 'Action-Log-Search-' . date('Y-m-d'),
            'dropdownOptions' => [
                'label' => 'Excel Export',
                'class' => 'btn btn-default',
                'itemsBefore' => [
                    '<li class="dropdown-header">Export All Data</li>',
                ],
            ],
        ]);
        
    }


    ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'id' => 'w0',
    ]); ?>



    <div class="row">
        <div class="col-xs-12 col-md-6">
            <p class="lead">المجموع</p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:50%">العدد :</th>
                        <td><?= $searchModel->sum_count?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">السعر الاجمالي النهائي :</th>
                        <td><?= $searchModel->sum_product_total_cost_final?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

