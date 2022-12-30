<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel common\models\InventoryOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'فواتير المشتريات');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-order-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>

        <?php
        if (Yii::$app->user->can('انشاء وتعديل فاتورة المشتريات')) {
            echo Html::a(Yii::t('app', 'انشاء فاتورة مشتريات'), ['create'], ['class' => 'btn btn-success']);
        }


        ?>
    </p>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php $gridColumns = [
        'id',
        [
            'attribute' => 'supplier_name',
            'value' => function ($model) {
                return $model->supplierTitle;
            },
            'format' => 'raw'
        ],
        [
            'attribute' => 'store_id',
            'value' => function ($model) {
                return $model->storeTitle;
            },
            'format' => 'raw',
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status' => 1])->all(), 'id', 'name'),
        ],
        'total_cost',
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return \common\components\CustomFunc::getFullDate($model->created_at);
            },
            'filter' => \kartik\date\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'created_at',
                'language' => 'ar',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-m-d '
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
                return \common\components\CustomFunc::getFullDate($model->updated_at);
            },
            'filter' => \kartik\date\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'updated_at',
                'language' => 'ar',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-m-d '
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
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, \common\models\InventoryOrder $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'template' => '{view} {update} {delete} {pdf}',  // the default buttons + your custom button
            'buttons' => [
                'pdf' => function ($url, $model, $key) {     // render your custom button
                    return Html::a('<i class="fa fa-file-pdf-o" aria-hidden="true"></i>', "report?id=" . $model->id, ['target' => '_blank']);
                }
            ],
            'visibleButtons' => [
                'visibleButtons' => [
                    'update' => function ($model) {
                        return Yii::$app->user->can('تعديل وحذف فواتير المشتريات');
                    },
                    'delete' => function ($model) {
                        return Yii::$app->user->can('تعديل وحذف فواتير المشتريات');
                    },
                    'view' => function ($model) {
                        return Yii::$app->user->can('انشاء وتعديل فاتورة المشتريات');
                    },
                    'pdf' => function ($model) {
                        return Yii::$app->user->can('انشاء وتعديل فاتورة المشتريات');
                    },
                ]
            ]

        ],
    ];


    if (Yii::$app->user->can('صلاحية الطباعة')) {
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
//        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'id' => 'w0',
    ]);
    ?>


</div>
