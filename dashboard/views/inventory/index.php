<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\daterange\DateRangePicker;

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

    <?php
    $stores =  \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name');
    if(!\Yii::$app->user->can('جميع المحلات مواد الافرع المخزن'))
    {
        $stores =  \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1,'id'=>\Yii::$app->user->identity->stores])->all(), 'id', 'name');
    }
    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
            'attribute' => 'product_name',
            'value' => function ($model) {
                return $model->productTitle ? Html::a($model->productTitle, '/product/view?id='.$model->product_id,['target'=>'_blank'] ) : '';
            },
            'label'=>"المادة",
            'format' => 'raw',
        ],
        [
            'attribute' => 'item_code',
           
            'label'=>"الكود",
            'format' => 'raw',
        ],
        [
            'attribute' => 'store_id',
            'value' => function($model){
                return $model->storeTitle;
            },
            'filter'=>$stores,
            'format' => 'raw',
        ],

        [
            'attribute' => 'available_status',
            'value' => function ($model) {
                return $model->getStatusText();
            },
            'format' => 'raw',
            'filter' => \common\models\Inventory::statusArray,
        ],
        [
            'attribute' => 'category_id',
            'label' => 'نوع القسم',
            'value' => function ($model) {
                return $model->product ? $model->product->categoryTitle :'';
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        [
            'attribute' => 'count',
            'visible' => Yii::$app->user->can('ظهور العدد بمواد الافرع بالمخزن'),
        ],
        [
            'attribute' => 'product.count_type',
            'value' => function ($model) {
                return $model->product ? $model->product->getCountTypeName('count_type'):'';
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\CountType::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],

        [
            'attribute' => 'product.price_1',
            'visible' => Yii::$app->user->can('سعر بيع 1'),
        ],
        [
            'attribute' => 'product.price_2',
            'visible' => Yii::$app->user->can('سعر بيع 2'),
        ],
        [
            'attribute' => 'product.price_3',
            'visible' => Yii::$app->user->can('سعر بيع 3'),
        ],
        [
            'attribute' => 'product.price_4',
            'visible' => Yii::$app->user->can('سعر بيع 4'),
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return \common\components\CustomFunc::getFullDate($model->created_at);
            },
            'filter' =>DateRangePicker::widget([
                'model' => $searchModel,
                'attribute' => 'created_at',
                'language' => 'en',
                'convertFormat' => true,
                'startAttribute' => 'created_at_from',
                'endAttribute' => 'created_at_to',
                'pluginOptions' => [
                    'timePicker' => true,
                    'timePickerIncrement' => 1,
                    'locale' => [
                        'applyLabel' => 'تطبيق',
                        'cancelLabel' => 'الغاء',
                        'format' => 'Y-m-d H:i:s',
                    ],
                    'startDate' => date('Y-m-d 00:00:00'), // Start of the day (12:00 AM)
                    'endDate' => date('Y-m-d 23:59:59'), // 12:00 PM of the same day
        
                ]
            ]),
        ],
        [
            'attribute' => 'created_by',
            'value' => function ($model) {
                return \common\components\CustomFunc::getUserName($model->created_by);
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
        ],
        [
            'attribute' => 'updated_at',
            'value' => function ($model) {
                return \common\components\CustomFunc::getFullDate($model->created_at);
            },
            'filter' =>DateRangePicker::widget([
                'model' => $searchModel,
                'attribute' => 'updated_at',
                'language' => 'en',
                'convertFormat' => true,
                'startAttribute' => 'updated_at_from',
                'endAttribute' => 'updated_at_to',
                'pluginOptions' => [
                    'timePicker' => true,
                    'timePickerIncrement' => 1,
                    'locale' => [
                        'applyLabel' => 'تطبيق',
                        'cancelLabel' => 'الغاء',
                        'format' => 'Y-m-d H:i:s',
                    ],
                    'startDate' => date('Y-m-d 00:00:00'), // Start of the day (12:00 AM)
                    'endDate' => date('Y-m-d 23:59:59'), // 12:00 PM of the same day
        
                ]
            ]),
        ],
        [
            'attribute' => 'updated_by',
            'value' => function ($model) {
                return \common\components\CustomFunc::getUserName($model->updated_by);
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
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
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'id' => 'w0',
        'rowOptions' =>function ($model){

            $minProductCount = $model->minProductCount?$model->minProductCount->count : 0;
            if($model->count == 0){
                return [
                    'class' => 'danger  time-set',
                    'data-text' => ' '
                ];
            }else if(!empty($model->count) && $model->product && $model->count < $minProductCount ){
                return [
                    'class' => 'warning  time-set',
                    'data-text' => ' '
                ];
            }
            return ['class' => 'time-set-notSet'];


        },
    ]);
    ?>




</div>
