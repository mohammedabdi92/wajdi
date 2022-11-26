<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
/* @var $model_product common\models\OrderProduct */

$this->registerJsFile(
    '@web/js/order.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
//$products = \common\models\Product::find()->where(['status'=>1])->all();
//$productList = [''=>"اختر ....."]+\yii\helpers\ArrayHelper::map($products, 'id', 'title');
//$productsArray = [];
$priceList = [];
//foreach ($products as $product)
//{
//    $productsArray[$product->id] =$product->attributes;
//}
$url = \yii\helpers\Url::to(['product/product-list']);
?>
<script>
    //var products = <?php //=\yii\helpers\Json::encode($productsArray);?>;

</script>

<div class="order-form">

    <?php $form = ActiveForm::begin(['enableClientValidation' => false, 'id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'customer_name')->textInput() ?>
    <?= $form->field($model, 'phone_number')->textInput() ?>
    <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر نوع العد .....'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <?php
    $stores = [];
    if(Yii::$app->user->can('كل المحلات'))
    {
        $stores = \common\models\Store::find()->where(['status'=>1])->all();
    }else{
        $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
    }
    echo $form->field($model, 'store_id')->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name'),
        'options' => ['placeholder' => 'اختر نوع العد .....'  ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>



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
                        <div class="panel-heading">
                            <h3 class="panel-title pull-right"><span class="panel-title-address">مادة: <?= ($i + 1) ?></span> </h3>
                            <div class="pull-left">

                                <button type="button" class="remove-item btn btn-danger btn-xs"><i
                                            class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php

                            // necessary for update action.
                            if (!$modelAddress->isNewRecord) {
                                echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                                $priceList = $modelAddress->getPriceList();
                            }


                            ?>

                            <div class="row">
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 " >

                                    <?php
                                    echo $form->field($modelAddress, "[{$i}]product_id")->widget(\kartik\select2\Select2::classname(), [
                                        'data' =>[$modelAddress->product_id=>$modelAddress->productTitle],
                                        'options' => ['placeholder' => 'اختر نوع العد .....','onchange' => 'productChange(this)'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 3,
                                            'language' => [
                                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                            ],
                                            'ajax' => [
                                                'url' => $url,
                                                'dataType' => 'json',
                                                'results' => new JsExpression('function(params) {  return params; }'),
                                                'cache' => true

                                            ],
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function(product) { return product.text; }'),
                                            'templateSelection' => new JsExpression('function (product) { return product.text; }'),
                                        ],
                                    ]);
                                    ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]count")->textInput() ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 " style=" height: 69px; ">
                                    <?= $form->field($modelAddress, "[{$i}]price_number")->radioList($priceList)->label('اختر') ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]amount")->textInput(['readonly' => true]) ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]total_product_amount")->textInput(['readonly' => true])?>
                                </div>
                                <?php if(Yii::$app->user->can('الخصم الافرادي فواتير المبيعات')):?>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                        <?= $form->field($modelAddress, "[{$i}]discount")->textInput() ?>
                                    </div>
                                <?php endif;?>

                                <div class="row col-md-12">
                                    <div class="col-sm-2"> <label> العدد داخل المحل</label> <br><label class="inventory_count" > </label></div>

                                </div>

                            </div><!-- .row -->

                        </div>
                    </div>
                <?php endforeach; ?>
                <div class=" col-md-2">
                    <?= $form->field($model, 'product_count')->textInput(['readonly' => true,'value'=>$model->product_count??1]) ?>
                </div>
                <div class=" col-md-2">
                    <?= $form->field($model, 'total_count')->textInput(['readonly' => true]) ?>
                </div>
            </div>

        </div>
        <?php \wbraganca\dynamicform\DynamicFormWidget::end(); ?>



        <div >
            <?php if(Yii::$app->user->can('الخصم الافرادي فواتير المبيعات')):?>
                <?= $form->field($model, 'total_price_discount_product')->textInput(['readonly' => true]) ?>
            <?php endif;?>

            <?php if(Yii::$app->user->can('الخصم الاجمالي فواتير المبيعات')):?>
                <?= $form->field($model, 'total_amount_without_discount')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'total_discount')->textInput(['readonly' => false]) ?>
            <?php endif;?>


            <?php if(Yii::$app->user->can('الدين والسداد فواتير المبيعات')):?>
                <?= $form->field($model, 'debt')->textInput() ?>
                <?= $form->field($model, 'repayment')->textInput() ?>
            <?php endif;?>

            <div class="panel panel-default">

                <div class="panel-body">

                    <?= $form->field($model, 'total_amount')->textInput(['readonly' => true]) ?>
                    <?php if(Yii::$app->user->can('باقي المبلغ للعميل في فواتير المبيعات')):?>
                        <?= $form->field($model, 'paid')->textInput() ?>
                        <?= $form->field($model, 'remaining')->textInput(['readonly' => true]) ?>
                    <?php endif;?>

                </div>

            </div>

            <?= $form->field($model, 'note')->textarea() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>

