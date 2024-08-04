<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
/* @var $model_product common\models\OrderProduct */

$this->registerJsFile(
    '@web/js/order.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);


$url = \yii\helpers\Url::to(['product/product-list']);
$priceList = [];
// print "<pre>";
// print_r($model->id);die;


?>


<div class="order-form">
    <?php $form = ActiveForm::begin([ 'enableClientValidation'=>false,
                'enableAjaxValidation'=>false,
        'id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'customer_name')->textInput() ?>
    <?= $form->field($model, 'phone_number')->textInput() ?>
    <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->customer_id=>$model->customerName],
        'options' => [
            'placeholder' => 'اختر ...', // Add a placeholder
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => \yii\helpers\Url::to(['customer/get-customers']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                'results' => new JsExpression('function(params) { return {q:params.term}; }'),
                'cache' => true

            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(product) { return product.text; }'),
            'templateSelection' => new JsExpression('function (product) { return product.text; }'),
        ],
    'pluginEvents' => [
        'select2:open' =>'function(params) {$(".select2-search__field")[0].focus()}'
    ]
        
    ]);
    ?>
    <?php if(Yii::$app->user->can('الدين والسداد فواتير المبيعات')): ?>
    <div id="dept_data_contaner">
        <?= $this->render("_customer_dept_history",["dept_data"=>$dept_data??null]) ?>
    </div>

    <?php

        $script = <<<JS
            $(document).on('change', '[id$=-customer_id]', function (item) {
            if( $(item.currentTarget).val())
            {
                $.post("/order/get-customer?id=" + $(item.currentTarget).val() , function (data) {
                    $("#dept_data_contaner").html(data);
                });
            }

        });
JS;
        $this->registerJs( $script , \yii\web\View::POS_READY );
    endif; ?>

    <?php
    $stores = [];
    if(Yii::$app->user->can('كل المحلات'))
    {
        $stores = \common\models\Store::find()->where(['status'=>1])->all();
    }else{
        $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
    }
    $single_store = null ;
    if(count($stores) == 1)
    {
        $single_store = $stores[0]->id;
    }
    if(empty($model->store_id))
    {
        $model->store_id = $single_store;
    }

    echo $form->field($model, 'store_id')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name'));
    ?>

    <div class=" col-md-12">
        <?php \yii\widgets\Pjax::begin(['id' => 'new_country']) ?>
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
                'ready_to_deliver'
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
                                <div style=" display: inline-block; padding: 0px  22px; ">
                                    <?php
                                    if($model->isNewRecord)
                                    {
                                        echo $form->field($modelAddress, "[{$i}]ready_to_deliver")->checkbox([
                                            'class' => 'select-on-check-all pull-right',//pull right the checkbox
                                            'label' => '<span class="checkmark"></span>',//pull left the label
                                            'style' => 'transform: scale(2);',//pull left the label
                                        ]);

                                    }
                                    ?>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-8">
                                    <?= $form->field($modelAddress, "[{$i}]title")->textInput(['readonly' => true,'value' =>$modelAddress->productTitle])->label('') ?>
                                    
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($modelAddress, "[{$i}]count_type_name")->textInput(['readonly' => true,'value' =>$modelAddress->productCountType])->label('') ?>
                                    </div>
                                    <div class="col-md-12">
                                    <?= $form->field($modelAddress, "[{$i}]item_location")->textInput(['readonly' => true,'value' =>$modelAddress->product->item_location??''])->label('') ?>
                                    
                                    </div>
                                </div>

                            </h3>


                            <div class="pull-left">

                                <button type="button" class="remove-item btn btn-danger btn-xs"><i
                                            class="glyphicon glyphicon-minus"></i></button>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php

                            // necessary for update action.
                            if (!$modelAddress->isNewRecord || $modelAddress->product_id) {
                                echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                                $priceList = $modelAddress->getPriceList();

                            }
                            $returnd_count = 0;
                            if(!$modelAddress->isNewRecord)
                            {
                                $returnd_count  = \common\models\Returns::find()->where(['order_id'=>$model->id,'product_id'=>$modelAddress->product_id])->sum("count");
                            }
                            $dameged_count = 0;
                            if(!$modelAddress->isNewRecord)
                            {
                                $dameged_count  = \common\models\Damaged::find()->where(['order_id'=>$model->id,'product_id'=>$modelAddress->product_id])->sum("count");
                            }


                            ?>

                            <div class="row">
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 " >

                                    <?php
                                    echo $form->field($modelAddress, "[{$i}]product_id")->widget(\kartik\select2\Select2::classname(), [
                                        'data' =>[$modelAddress->product_id=>$modelAddress->productTitle],
                                        'options' => ['placeholder' => 'اختر المادة .....','onchange' => 'productChange(this)'
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => false,
                                            'minimumInputLength' => 3,
                                            'language' => [
                                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                                            ],
                                            'ajax' => [
                                                'url' => $url,
                                                'dataType' => 'json',
                                                'data' => new JsExpression('function(params) { return {q:params.term,store_id:$("#order-store_id").val()}; }'),
                                                'results' => new JsExpression('function(params) { return {q:params.term}; }'),
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
                                    <?= $form->field($modelAddress, "[{$i}]price_number")->radioList($priceList,['unselect' => null])->label('اختر') ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]amount")->textInput(['readonly' => !Yii::$app->user->can('تعديل السعر الافرادي في المبيعات')]) ?>
                                </div>
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                    <?= $form->field($modelAddress, "[{$i}]total_product_amount")->textInput(['readonly' => true])?>
                                </div>
                                <?php if(Yii::$app->user->can('الخصم الافرادي فواتير المبيعات')):?>
                                    <div class="col-4 col-sm-4 col-md-4 col-lg-2 ">
                                        <?= $form->field($modelAddress, "[{$i}]discount")->textInput() ?>
                                    </div>
                                <?php endif;?>

                                <div style=" display: -webkit-inline-box; width: 100%; ">
                                    <div class="col-sm-2"> <label> العدد داخل المحل</label> <br><label class="inventory_count" > </label></div>
                                    <?php if($returnd_count || $dameged_count): ?>
                                        <div class="col-sm-2"> <label> المرجع </label> <br><label><?=$returnd_count?></label></div>
                                        <div class="col-sm-2"> <label> التالف </label> <br><label><?=$dameged_count?></label></div>
                                    <?php endif; ?>
                                    <?php if(Yii::$app->user->can('اظهار التكلفة والربح في فاتورة المبيعات والارشيف')): ?>
                                        <button type="button" class="btn btn-secondary"  id = 'titleElement_<?= $i ?>' data-toggle="tooltip" data-placement="top" title="<?= (!empty($modelAddress->product)? $modelAddress->product->price:'') ?>">
                                        🪙
                                        </button>
                                    
                                    <?php
                                        endif; 
                                    ?>
                                 
                                </div>
                                <?php echo $form->field($modelAddress, "[{$i}]orignal_cost")->hiddenInput(['value'=>!empty($modelAddress->product)? $modelAddress->product->price:''])->label('') ;
                               
                                ?>
                                



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
                <?= $form->field($model, 'product_count')->textInput(['readonly' => true,'value'=>$model->product_count??1]) ?>
            </div>
            <div class=" col-md-2">
                <?= $form->field($model, 'total_count')->textInput(['readonly' => true]) ?>
            </div>
        </div>
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
                <?= $form->field($model, 'dept_note')->textarea() ?>
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

            <?php
            if(!$model->isNewRecord)
            {
                echo '<label>المرجع</label>';
                echo \yii\grid\GridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([ 'query' => \common\models\Returns::find()->where(['order_id'=>$model->id])]),
                    'columns' => [
                        'id',
                        'count',
                        [
                            'attribute' => 'product_name',
                            'value' => function($model){
                                return $model->productTitle;
                            },
                            'label'=>"المادة",
                            'format' => 'raw',

                        ],
                        'amount',
                        'returner_name',
                        [
                            'attribute' => 'created_at',
                            'value' => function($model){
                                return \common\components\CustomFunc::getFullDate($model->created_at);
                            },
                        ],
                    ],
                ]);
                echo '<label>مجموع :</label>'.\common\models\Returns::find()->where(['order_id'=>$model->id])->sum('amount').'<br>';
                echo '<label>التالف</label>';
                echo \yii\grid\GridView::widget([
                    'dataProvider' => new \yii\data\ActiveDataProvider([ 'query' => \common\models\Damaged::find()->where(['order_id'=>$model->id])]),
                    'columns' => [
                        'id',
                        'count',
                        [
                            'attribute' => 'product_name',
                            'value' => function($model){
                                return $model->productTitle;
                            },
                            'label'=>"المادة",
                            'format' => 'raw',

                        ],
                        'amount',
                        [
                            'attribute' => 'created_at',
                            'value' => function($model){
                                return \common\components\CustomFunc::getFullDate($model->created_at);
                            },
                        ],
                    ],
                ]);
            }
             ?>
             <?php if(Yii::$app->user->can('اظهار خسارة الفاتورة بالمبيعات') ): ?>
                <div class="form-group ">
<h1 class="control-label" id = "order_status" style=" color: red; "></h1>

</div>
             
               
            <?php endif;?>
            <?php if(Yii::$app->user->can('اظهار التكلفة والربح في فاتورة المبيعات والارشيف')): ?>
                <button type="button" class="btn btn-secondary"  id = "titleElement1_all" data-toggle="tooltip" data-placement="top" title="<?= $model->earn_the_bill ?>">
                             🛞
                    </button>
               
            <?php
               
                endif; 
                echo $form->field($model, "earn_the_bill")->hiddenInput()->label('');
            ?>
           
            <div class="form-group">
                <?php if(!Yii::$app->user->can('عدم حفظ فاتورة المبيعات')):?>
                <?= Html::submitButton('حفظ', ['class' => 'btn btn-success','name' => 'save']) ?>
                <br>
                <br>
                <?php if($model->isNewRecord):?>
                <?= Html::submitButton('حفظ للارشيف', ['class' => 'btn btn-primary','name' => 'draft']) ?>
                <?php endif;?>

                <?php endif;?>
            </div>
        </div>

        <?php \yii\widgets\Pjax::end() ?>


    </div>
    <?php ActiveForm::end(); ?>
</div>








