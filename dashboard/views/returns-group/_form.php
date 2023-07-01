<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\OrderProduct;

/* @var $this yii\web\View */
/* @var $model common\models\Returns */
/* @var $form yii\widgets\ActiveForm */


$this->registerJsFile(
    '@web/js/orderReturned.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);


$url = \yii\helpers\Url::to(['product/product-list']);
$product_data = [''=>"اختر ..."];
if($model->order_id)
{
    $OrderProducts = OrderProduct::find()->where(['order_id'=>$model->order_id])->all();
    if($OrderProducts)
    {
        $count = 0;
        foreach ($OrderProducts as $OrderProduct){
            $product_data[$OrderProduct->product_id] = $OrderProduct->product->title;
        }
    }
    
}
// echo "<pre>";
// print_r($model_product);die;
?>

<div class="damaged-form">

<?php
 $form = ActiveForm::begin([
    'enableClientValidation'=>false,
    'enableAjaxValidation'=>false,
    'id' => 'dynamic-form'
    ]); 
    
    
    ?>




    <?php
    echo $form->field($model, "order_id")->textInput(['id' => 'order_id','placeholder' => 'اختر رقم الطلب .....']);
  
    ?>

    <?= $form->field($model, 'returner_name')->textInput() ?>
    <?= $form->field($model, 'note')->textarea() ?>


   
    <div class=" col-md-12">
       
        <?php \wbraganca\dynamicform\DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.container-items', // required: css class selector
            'widgetItem' => '.item', // required: css class
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => $model_product[0],
            'formId' => 'dynamic-form',
            'formFields' => [
                'product_id',
                'count',
                'amount'
            ],
        ]); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-envelope"></i> المواد
                <button type="button" class="pull-left add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> اضافة مادة </button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body container-items"><!-- widgetContainer -->
                <?php foreach ($model_product as $i => $modelAddress): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading" >
                            <h3 class="panel-title pull-right" style=" width: 95%; ">
                                <span class="panel-title-address">مادة: <?= ($i + 1) ?></span>
                                <br>
                            </h3>
                            <div class="pull-left">
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i  class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 " >
                                    <?php
                                    echo $form->field($modelAddress, "[{$i}]product_id")->widget(\kartik\depdrop\DepDrop::classname(), [
                                        'data' => $product_data,
                                        'pluginOptions' => [
                                            'depends' => ['order_id'],
                                            'placeholder' => 'اختر ...',
                                            'url' => Url::to(['/order/order-products']),
                                
                                        ],
                                    ]);
                                    if (!$modelAddress->isNewRecord ) {
                                        echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                                    }
                                    ?>
                                </div>
                                
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]count")->textInput() ?>
                                </div>
                            
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]amount")->textInput() ?>
                                </div>
                            </div><!-- .row -->

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <div class="panel-heading">

                <button type="button" class="pull-left add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> اضافة مادة </button>
                <div class="clearfix"></div>
            </div>

        </div>
        <?php \wbraganca\dynamicform\DynamicFormWidget::end(); ?>


        <div class="row " style=" border-width: medium; border-style: solid; border-color: #34495e; margin: 0px; margin-bottom: 10px;">
            <div class=" col-md-2">
                <?= $form->field($model, 'total_count')->textInput(['readonly' => true,'value'=>$model->total_count??0]) ?>
            </div>
            <div class=" col-md-2">
                <?= $form->field($model, 'total_amount')->textInput(['readonly' => true,'value'=>$model->total_amount??0]) ?>
            </div>
            <div class=" col-md-2">
            </div>
        </div>
        <div >
            


           
            <div class="form-group">
       
    </div>
        </div>

        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>
  
    <?php ActiveForm::end(); ?>

</div>
