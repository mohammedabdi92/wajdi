<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Shortage $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shortage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>

    <?php
    $url = \yii\helpers\Url::to(['product/product-list']);
    echo $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->product_id=>$model->productTitle],
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => false,
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
    ]);
    ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
