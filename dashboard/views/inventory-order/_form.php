<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrder */
/* @var $model_product common\models\InventoryOrderProduct */
/* @var $form yii\widgets\ActiveForm */
$this->registerJsFile(
    '@web/js/inventory.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$url = \yii\helpers\Url::to(['product/product-list']);
?>


<div class="inventory-order-form">
    <?php $form = ActiveForm::begin(['enableClientValidation'=>false,'id' => 'dynamic-form']); ?>
    <div class=" col-md-12">


        <?= $form->field($model, 'supplier_name')->textInput() ?>
        <?= $form->field($model, 'phone_number')->textInput() ?>

        <?php
        echo $form->field($model, 'supplier_id')->widget(\kartik\select2\Select2::classname(), [
            'data' =>[''=>'اختار المورد .....']+\yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name'),
            'options' => ['placeholder' => 'اختر نوع العد .....'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
        <?= $form->field($model, 'inventory_order_id')->textInput() ?>
        <label>تاريخ الفاتورة الورقي</label>
        <?=   DatePicker::widget([
              'model' => $model,
              'attribute' => 'inventory_order_date',
//              'language' => 'en',
      //'dateFormat' => 'yyyy-MM-dd',
  ]); ?>
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


    </div>


    <div class=" col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> المواد</h4></div>
        <div class="panel-body">
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
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

            <div class="container-items"><!-- widgetContainer -->
                <?php foreach ($model_product as $i => $modelAddress): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-right">مادة</h3>
                            <div class="pull-left">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php
                            // necessary for update action.
                            if (! $modelAddress->isNewRecord) {
                                echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                            }
                            ?>

                            <div class="row">
                                <div class="col-sm-2">
                                    <?php
                                    echo $form->field($modelAddress, "[{$i}]product_id")->widget(\kartik\select2\Select2::classname(), [
                                        'options' => ['placeholder' => 'اختر نوع العد .....'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'minimumInputLength' => 3,
                                            'language' => [
                                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                            ],
                                            'ajax' => [
                                                'url' => $url,
                                                'dataType' => 'json',
                                                'data' => new JsExpression('function(params) {  return {q:params}; }'),
                                                'results' => new JsExpression('function(params) {  return params; }')
                                            ],
                                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                            'templateResult' => new JsExpression('function(product) { return product.text; }'),
                                            'templateSelection' => new JsExpression('function (product) { return product.text; }'),
                                        ],
                                    ]);
                                    ?>

                                </div>
                                <div class="col-sm-2">
                                    <?= $form->field($modelAddress, "[{$i}]count")->textInput() ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $form->field($modelAddress, "[{$i}]product_cost")->textInput() ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $form->field($modelAddress, "[{$i}]product_total_cost")->textInput() ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $form->field($modelAddress, "[{$i}]product_cost_final")->textInput(['readonly' => true]) ?>
                                </div>
                                <div class="col-sm-2">
                                    <?= $form->field($modelAddress, "[{$i}]product_total_cost_final")->textInput(['readonly' => true]) ?>
                                </div>
                            </div><!-- .row -->
                            <div class="row">
                                <div class="col-sm-2"> <label> سعر التكلفة</label> <br><label class="order_product_price" > </label></div>
                                <div class="col-sm-2"> <label> اخر سعر</label> <br><label class="last_price" > </label></div>
                                <div class="col-sm-2"> <label> اقل سعر</label> <br><label class="min_price" > </label></div>
                            </div>

                    </div>
                    </div>
                <?php endforeach; ?>

            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    </div>


    <div class=" col-md-12">

        <?= $form->field($model, 'discount_percentage')->textInput() ?>
        <?= $form->field($model, 'discount')->textInput() ?>

        <?= $form->field($model, 'tax_percentage')->textInput() ?>
        <?= $form->field($model, 'tax')->textInput() ?>

        <?= $form->field($model, 'total_count')->textInput(['readonly' => true]) ?>

        <?= $form->field($model, 'debt')->textInput() ?>
        <?= $form->field($model, 'repayment')->textInput() ?>
        <?= $form->field($model, 'total_cost')->textInput(['readonly' => true]) ?>
        <?= $form->field($model, 'note')->textarea() ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
