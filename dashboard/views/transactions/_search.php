<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var common\models\TransactionsSearch $model */
/** @var yii\widgets\ActiveForm $form */

?>

<div class="transactions-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->customer_id=>$model->customerName],
        'options' => [
            'placeholder' => 'اختر ...', // Add a placeholder
            'allowClear' => true, // Ensure allowClear is true
        ],
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

    <?= $form->field($model, 'order_id') ?>

   

    <?= $form->field($model, 'type')->dropDownList([''=>'...'] + $model::typeArray ); ?>

    <label> التاريخ</label>
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

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'isDeleted') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'حذف'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
