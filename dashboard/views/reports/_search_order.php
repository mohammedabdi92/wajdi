<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\InventorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inventory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['order'],
        'method' => 'get',
        'id'=>'order-product'
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
    <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر العميل .....'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <?= $form->field($model, 'created_by')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>


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





    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'بحث'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'حذف'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
