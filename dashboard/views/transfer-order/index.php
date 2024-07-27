<?php

use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TransferOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'النقليات');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="transfer-order-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء طلب نقل'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $gridColumns= [
        'id',
        [
            'attribute' => 'product_name',
            'value' => function($model){
                return $model->productTitle;
            },
            'label'=>"المادة",
            'format' => 'raw',

        ],
        [
            'attribute' => 'from',
            'value' => function($model){
                return $model->fromTitle;
            },
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        [
            'attribute' => 'to',
            'value' => function($model){
                return $model->toTitle;
            },
            'filter'=>\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        'count',
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
        
                ],
                'pluginEvents' => [
                    'cancel.daterangepicker' => 'function(ev, picker) {
                        $("[id$=-created_at]").val("").trigger("change");
                        $("[id$=-created_at_from]").val("").trigger("change");
                        $("[id$=-created_at_to]").val("").trigger("change");
                    }',
                ],
            ]),
        ],
        [
            'attribute' => 'created_by',
            'value' => function ($model) {
                return \common\components\CustomFunc::getUserName($model->created_by);
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
    ]);
    ?>




</div>
