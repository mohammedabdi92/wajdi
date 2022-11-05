<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'المواد');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->can('تعديل المواد')) {
            echo Html::a(Yii::t('app', 'انشاء مادة'), ['create'], ['class' => 'btn btn-success']);
        }
        ?>

    </p>

    <?php

    $gridColumns = [
        'id',
        [
            'attribute' => 'image_name',
            'value' => function ($model) {
                return $model->getImageUrl();
            },
            'format' => ['image', ['width' => '100', 'height' => '100']],
        ],
        'title:ntext',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return $model->getStatusText();
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'category_id',
            'value' => function ($model) {
                return $model->categoryTitle;
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        [
            'attribute' => 'count_type',
            'value' => function ($model) {
                return $model->getCountTypeName('count_type');
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\CountType::find()->all(), 'id', 'name'),
            'format' => 'raw',
        ],
        [
            'attribute' => 'price',
            'format' => 'raw',
            'visible' => Yii::$app->user->can('سعر التكلفة بالواجهة على المواد'),
        ],
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
            'attribute' => 'updated_by',
            'value' => function ($model) {
                return \common\components\CustomFunc::getUserName($model->updated_by);
            },
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')
        ],

    ];
    if(Yii::$app->user->can('اظهار الحد الأدنى بواجهة المواد'))
    {
        foreach (\common\models\Store::find()->where(['status' => 1])->all() as $store) {

            $gridColumns[] = [
                'attribute' => 'min_counts[' . $store->id . ']',
                'value' => function ($model) use ($store) {
                    return $model->min_counts[$store->id] ?? "غير محدد";
                },
                'label' => ' حد ادنى ' . $store->name
            ];
        }
    }

    $gridColumns[] = [
        'class' => ActionColumn::className(),
        'urlCreator' => function ($action, \common\models\Product $model, $key, $index, $column) {
            return Url::toRoute([$action, 'id' => $model->id]);
        },
        'visibleButtons' => [
            'update' => function ($model) {
                return Yii::$app->user->can('تعديل المواد');
            },
            'view' => function ($model) {
                return Yii::$app->user->can('تعديل المواد');
            },
        ]
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
        'pager' => [
            'options' => ['class' => 'pagination'],   // set clas name used in ui list of pagination
            'prevPageLabel' => '<',   // Set the label for the "previous" page button
            'nextPageLabel' => '>',   // Set the label for the "next" page button
            'firstPageLabel' => '<<',   // Set the label for the "first" page button
            'lastPageLabel' => '>>',    // Set the label for the "last" page button
            'maxButtonCount' => 20,    // Set maximum number of page buttons that can be displayed
        ],
        'columns' => $gridColumns,
        'id' => 'w0',
    ]);
    ?>


</div>
