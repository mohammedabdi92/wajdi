<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'المواد';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if (Yii::$app->user->can('تعديل المواد')) {
            echo Html::a('انشاء مادة', ['create'], ['class' => 'btn btn-success']);
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
        'item_code',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return $model->getStatusText();
            },
            'format' => 'raw',
            'filter' => \common\models\Product::statusArray,
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
        'class' => ActionColumn::className(),'icons'=>[
                'eye-open' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                'pencil' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
                'trash' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
            ],
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
