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
$this->registerJsFile(
    '@web/js/product.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<script>
    var price_profit_rate_1 = <?=$price1?>;
    var price_profit_rate_2 = <?=$price2?>;
    var price_profit_rate_3 = <?=$price3?>;
</script>
<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>


    <?= $form->field($model, 'category_id')->dropDownList([''=>'اختر ......']+\yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'count_type')->dropDownList([''=>'اختر ......']+\common\components\Constants::countTypesArray); ?>

    <?= $form->field($model, 'price')->textInput()->label('السعر ') ?>

    <?= $form->field($model, 'price_1')->textInput()->label('السعر الاول '.($price1*100).'%') ?>

    <?= $form->field($model, 'price_2')->textInput()->label('السعر الثاني '.($price2*100).'%') ?>

    <?= $form->field($model, 'price_3')->textInput()->label('السعر الثالث '.($price3*100).'%') ?>

    <?= $form->field($model, 'min_number')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
