<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'فواتير المبيعات');
$this->params['breadcrumbs'][] = $this->title;
$modelAddress = new  \common\models\OrderProduct();
$form = new \yii\widgets\ActiveForm();
$url = \yii\helpers\Url::to(['product/product-list']);

$this->registerJsFile(
    '@web/js/indexOrder.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$totalSum = 0;
if(Yii::$app->user->can('اظهار المجموع في صفحة البيع')){
    $totalSum = $dataProvider->query->sum('returns_group.total_amount');
    $totalSum = round($totalSum, 2); // Rounds to 2 decimal places
}


?>

<script>

</script>
<div class="order-index">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'انشاء فاتورة بيع'), ['create'], ['class' => 'btn btn-success']) ?>
    <div class="row item" style=" padding: 9px; border: 1px solid #ddd; ">
        <div class="row">
            <div class="col-4 col-sm-4 col-md-4 col-lg-2 " >
                <?php
                $stores = [];
                if(Yii::$app->user->can('كل المحلات'))
                {
                    $stores = \common\models\Store::find()->where(['status'=>1])->all();
                }else{
                    $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
                }
                $single_store = null ;
                if(count($stores) == 1)
                {
                    $single_store = $stores[0]->id;
                }

                echo $form->field($modelAddress, 'store_id')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name'));
                ?>

            </div>
        </div>

        <div class="col-4 col-sm-4 col-md-4 col-lg-2 " >


            <?php
            echo $form->field($modelAddress, "product_id")->widget(\kartik\select2\Select2::classname(), [
                'data' =>[$modelAddress->product_id=>$modelAddress->productTitle],
                'options' => ['placeholder' => 'اختر المادة .....','onchange' => 'productChange(this)'
                ],
                'pluginOptions' => [
                    'allowClear' => $modelAddress->isNewRecord,
                    'minimumInputLength' => 3,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => $url,
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term,store_id:$("#order-store_id").val()}; }'),
                        'results' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache' => true

                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(product) { return product.text; }'),
                    'templateSelection' => new JsExpression('function (product) { return product.text; }'),
                ],
            ]);
            ?>
        </div>
        <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
            <?= $form->field($modelAddress, "count")->textInput() ?>
        </div>
        <div class="col-4 col-sm-4 col-md-4 col-lg-2 " style=" height: 69px; ">
            <?= $form->field($modelAddress, "price_number")->radioList([],['unselect' => null])->label('اختر') ?>
        </div>
        <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
            <?= $form->field($modelAddress, "amount")->textInput(['readonly' => !Yii::$app->user->can('تعديل السعر الافرادي في المبيعات')]) ?>
        </div>
        <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
            <?= $form->field($modelAddress, "total_product_amount")->textInput(['readonly' => true])?>
        </div>
        <div style=" display: -webkit-inline-box; width: 100%; ">
            <div class="col-sm-2"> <label> العدد داخل المحل</label> <br><label class="inventory_count" > </label></div>

        </div>


    </div>
    </p>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $gridColumns = [


        'id',
        [
            'attribute' => 'customer_name',
            'value' => function($model){
                return $model->customerTitle;
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'store_id',
            'value' => function($model){
                return $model->storeTitle;
            },
            'format' => 'raw',
            'filter' => \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name'),
        ],
        [
            'attribute' => 'total_amount',
            'footer' => $totalSum, // Format the total sum
            'footerOptions' => ['style' => 'font-weight: bold;'], // Optional: make the footer bold
        ],

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
            'urlCreator' => function ($action, \common\models\Order $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
            'icons'=>[
                'eye-open' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                'pencil' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
                'trash' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:2em;overflow:visible;vertical-align:-.125em;width:2em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
            ],
            'template' => '{view} {update} {delete} {pdf}',  // the default buttons + your custom button
            'buttons' => [
                'pdf' => function ($url, $model, $key) {     // render your custom button
                    return Html::a('<i class="fa fa-file-pdf-o " aria-hidden="true" style=" font-size: 2em; "></i>',"report?id=".$model->id."&v=".time(),['target'=>'_blank']);
                }
            ],
            'visibleButtons' => [
                'update' => function ($model) {
                    return Yii::$app->user->can('تعديل وحذف فواتير المبيعات');
                },
                'delete' => function ($model) {
                    return Yii::$app->user->can('تعديل وحذف فواتير المبيعات');
                }
            ]
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
        'showFooter' => Yii::$app->user->can('اظهار المجموع في صفحة البيع'),
//        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'id' => 'w0',
    ]);
    ?>



</div>
