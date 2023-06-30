<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'stagnant_month')->label('تاريخ الركود بالاشهر للمادة') ?>
    <?= $form->field($model, 'id')->textInput() ?>
    <?= $form->field($model, 'product_id')->textInput() ?>
    <?= $form->field($model, 'store_id')->dropDownList([''=>'اختر المحل ....']+$stores);?>
    <?= $form->field($model, 'available_status')->dropDownList([''=>'اختر  ....']+\common\models\Inventory::statusArray);?>
    <?= $form->field($model, 'category_id')->dropDownList([''=>'اختر  ....']+\yii\helpers\ArrayHelper::map(\common\models\ProductCategory::find()->all(), 'id', 'name'))->label('نوع القسم');?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
