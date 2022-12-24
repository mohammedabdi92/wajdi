<?php

use yii\helpers\Html;
use kartik\export\ExportMenu;

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
    $gridColumns = [

        'id',
        [
            'attribute' => 'product_id',
            'value' => function($model){
                return $model->productTitle;
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'store_id',
            'value' => function($model){
                return $model->storeTitle;
            },
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'),
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
            if(!empty($model->count) && $model->product && $model->count < $model->product->min_number ){
                return [
                    'class' => 'danger  time-set',
                    'data-text' => ' '
                ];
            }
            return ['class' => 'time-set-notSet'];


        },
    ]);
    ?>




</div>
