<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'بيع الفواتير');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>


    <?php echo $this->render('_search_order', ['model' => $searchModel]); ?>
    <?php
    $gridColumns = [
        'id',
        [
            'attribute' => 'store_id',
            'value' => function ($model) {
                return $model->storeTitle;
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        [
            'attribute' => 'customer_id',
            'value' => function ($model) {
                return $model->customer->name ?? '';
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        'total_amount_without_discount',
        'total_price_discount_product',
        'total_discount',
        'debt',
        'repayment',
        'total_amount',
        [
            'attribute' => 'created_by',
            'value' => function ($model) {
                return \common\components\CustomFunc::getUserName($model->created_by);
            },
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
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
                        <th style="width:50%">مجموع القيمة بدون الخصم :</th>
                        <td><?= $searchModel->total_amount_without_discount_sum ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">مجموع الخصم :</th>
                        <td><?= $searchModel->total_discount_sum ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">مجموع الديون :</th>
                        <td><?= $searchModel->debt_sum ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%">مجموع السداد :</th>
                        <td><?= $searchModel->repayment_sum ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%"> مجموع القيمة بعد الخصم:</th>
                        <td><?= $searchModel->total_amount_sum ?></td>
                    </tr>
                    <tr>
                        <th style="width:50%"> مجموع الربح:</th>
                        <td><?= $searchModel->total_profit ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


