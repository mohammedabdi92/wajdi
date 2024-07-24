<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;


/* @var $this yii\web\View */
/* @var $model common\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
$stores = [];
if(Yii::$app->user->can('كل المحلات'))
{
    $stores = \common\models\Store::find()->where(['status'=>1])->all();
}else{
    $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
}
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>
    <?php
    $url = \yii\helpers\Url::to(['product/product-list']);
    echo $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->product_id=>$model->productTitle],
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                'results' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                'cache' => true

            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(product) { return product.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (product) { return product.text; }'),
        ],
    ])->label('المادة');
    ?>

   
     <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->customer_id=>$model->customerName],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to(['customer/get-customers']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                'results' => new JsExpression('function(params) { return {q:params.term}; }'),
                'cache' => true

            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(product) { return product.text; }'),
            'templateSelection' => new JsExpression('function (product) { return product.text; }'),
        ],
    'pluginEvents' => [
        'select2:open' =>'function(params) {$(".select2-search__field")[0].focus()}'
    ]
        
    ]);
    ?>

<?php
    if (count($stores) > 1) {
        echo $form->field($model, 'store_id')->dropDownList(['' => 'المحل ... '] + \yii\helpers\ArrayHelper::map($stores, 'id', 'name'));
    }
    ?>

    <?= $form->field($model, 'total_amount') ?>

    <?php if (Yii::$app->user->can('تعديل تاريخ البحث في المبيعات')): ?>
    <label>تاريخ الانشاء</label>
    <?= DateRangePicker::widget([
        'model' => $model,
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
    ]); ?>
    <br>



    <?php echo $form->field($model, 'created_by')->dropDownList(['' => "..."] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')) ?>

    <label>تاريخ التعديل</label>
    <?= DateRangePicker::widget([
        'model' => $model,
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
    ]); ?>
    <br>
    <?php echo $form->field($model, 'updated_by')->dropDownList(['' => "..."] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')) ?>
    <?php endif; ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
