<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrderProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-order-product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['inventory-order-product'],
        'method' => 'get',
        'id'=>'inventory-order-product'
    ]); ?>

    <?=  $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Product::find()->where(['status'=>1])->all(), 'id', 'title'),
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'inventory_order_id') ?>

    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
    <?= $form->field($model, 'supplier_id')->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>'اختار المورد .....']+\yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر نوع العد .....'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('المورد'); ?>




    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'حذف'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
