<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Damaged */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(
    '@web/js/damaged.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<div class="damaged-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    echo $form->field($model, "order_id")->textInput(['id' => 'order_id','readonly' => true,'placeholder' => 'اختر رقم الطلب .....']);
    ?>
    <div class="col-xs-12 col-sm-12 invoice-col">
        
        <div id="order_details">

        </div>
       
    </div>
    <?php
     $data =($model->product_id)? [$model->product_id=>$model->product->title]:[];
    echo $form->field($model, 'product_id')->widget(\kartik\depdrop\DepDrop::classname(), [
        
        'data'=>$data,
        'type' => DepDrop::TYPE_SELECT2,
        'options' => ['id' => 'product_id','disabled' => true, 'placeholder' => 'Select ...'],
        'select2Options' => ['pluginOptions' => ['allowClear' => true]],
        'pluginOptions' => [
            'depends' => ['order_id'],
            'url' => Url::to(['/order/order-products']),
        ],
    ]);
    ?>


    <?= $form->field($model, 'count')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'cost_value')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'total_amount')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'customer_note')->textarea() ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>
    
    <?= $form->field($model, 'inventory_order_id')->textInput() ?>
    <?= $form->field($model, 'supplyer_price')->textInput() ?>
    <?= $form->field($model, 'supplyer_pay_amount')->textInput() ?>

    <?= $form->field($model, 'supplier_note')->textarea() ?>
    <?= $form->field($model, 'status_note_id')->dropDownList([''=>"اختيار ...."]+$model::statusNoteArray); ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
