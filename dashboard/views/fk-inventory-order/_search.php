<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrderSearch */
/* @var $form yii\widgets\ActiveForm */
$stores = [];
if(Yii::$app->user->can('كل المحلات'))
{
    $stores = \common\models\Store::find()->where(['status'=>1])->all();
}else{
    $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
}
?>

<div class="inventory-order-search">

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

    <?= $form->field($model, "supplier_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر المورد .....'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'store_id')->dropDownList([''=>'المحل ... ']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name')) ?>

    <?= $form->field($model, 'total_cost') ?>
    <label>تاريخ الانشاء</label>
    <?=   \kartik\date\DatePicker::widget([
        'model' => $model,
        'attribute' => 'created_at',
        'language' => 'ar',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-m-d '
        ]
    ]); ?>
    <?php  echo $form->field($model, 'created_by')->dropDownList([''=>"..."]+\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')) ?>
    <label>تاريخ التعديل</label>
    <?=   \kartik\date\DatePicker::widget([
        'model' => $model,
        'attribute' => 'updated_at',
        'language' => 'ar',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-m-d '
        ]
    ]); ?>
    <?php  echo $form->field($model, 'updated_by')->dropDownList([''=>"..."]+\yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')) ?>
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
