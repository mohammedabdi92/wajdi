<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\TransferOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>

    <?= $form->field($model, 'to')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
    <?php
    $url = \yii\helpers\Url::to(['product/product-list']);
    echo $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->product_id=>$model->productTitle],
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => false,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term,store_id:$("#transferorder-from").val()}; }'),
                'results' => new JsExpression('function(params) { return {q:params.term}; }'),
                'cache' => true

            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(product) { return product.text; }'),
            'templateSelection' => new JsExpression('function (product) { return product.text; }'),
        ],
    ]);
    ?>
    <?= $form->field($model, 'count')->textInput() ?>





    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
