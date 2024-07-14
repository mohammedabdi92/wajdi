<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrderProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-order-product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'inventory_order_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'product_total_cost')->textInput() ?>

    <?= $form->field($model, 'product_cost')->textInput() ?>

    <?= $form->field($model, 'count')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
