<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
$settings = Yii::$app->settings;
$price1 = $settings->get('price', 'price_profit_rate_1');
$price2 = $settings->get('price', 'price_profit_rate_2');
$price3 = $settings->get('price', 'price_profit_rate_3');
$price4 = $settings->get('price', 'price_profit_rate_4');
$this->registerJsFile(
    '@web/js/product.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<script>
    var price_profit_rate_1 = <?=$price1?>;
    var price_profit_rate_2 = <?=$price2?>;
    var price_profit_rate_3 = <?=$price3?>;
    var price_profit_rate_4 = <?=$price4?>;
</script>
<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>


    <?php
    echo $form->field($model, 'category_id')->widget(\kartik\select2\Select2::classname(), [
        'data' => [''=>'اختر ......']+\yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر القسم .....'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?php
    echo $form->field($model, 'count_type')->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>'اختر ......']+\yii\helpers\ArrayHelper::map(\common\models\CountType::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <?= $form->field($model, 'price_pf_vat')->textInput()->label('السعر قبل الضريبة ') ?>
    <?= $form->field($model, 'vat')->textInput()->label('الضريبة %') ?>
    <?= $form->field($model, 'price_discount_percent')->textInput()->label('نسبة الخصم %') ?>
    <?= $form->field($model, 'price_discount_amount')->textInput()->label('قيمة الخصم ') ?>


    <?= $form->field($model, 'price')->textInput()->label('السعر ') ?>

    <?= $form->field($model, 'price_1')->textInput()->label('السعر الاول '.($price1*100).'%') ?>

    <?= $form->field($model, 'price_2')->textInput()->label('السعر الثاني '.($price2*100).'%') ?>

    <?= $form->field($model, 'price_3')->textInput()->label('السعر الثالث '.($price3*100).'%') ?>

    <?= $form->field($model, 'price_4')->textInput()->label('السعر الرابع '.($price4*100).'%') ?>


    <?php foreach (\common\models\Store::find()->where(['status'=>1])->all() as $store):  ?>

    <div class="row col-md-2">
       <b> <?= $store->name ?></b>
        <?= $form->field($model, 'min_counts['.$store->id.']')->textInput() ?>
    </div>

    <?php endforeach; ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>



    <?php
    echo $form->field($model, 'imageFile')->widget(\kartik\file\FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview'=>$model->getImageUrl()??false,
            'initialPreviewAsData' => true,
            'showCaption' => true ,
            'showRemove' => false ,
            'showUpload' => false ,
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
