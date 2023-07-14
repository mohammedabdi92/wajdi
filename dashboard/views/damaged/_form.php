<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Damaged */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(
    '@web/js/orderReturned.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<div class="damaged-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    echo $form->field($model, "order_id")->textInput(['id' => 'order_id','placeholder' => 'اختر رقم الطلب .....']);
    echo $form->field($model, 'product_id')->widget(\kartik\depdrop\DepDrop::classname(), [
        'type' => DepDrop::TYPE_SELECT2,
        'options' => ['id' => 'product_id', 'placeholder' => 'Select ...'],
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'depends' => ['order_id'],
            'url' => Url::to(['/order/order-products']),

        ],


    ]);
    ?>

    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
