<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\ServiceCenter;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var common\models\Maintenance $model */
/** @var yii\widgets\ActiveForm $form */


// Fetch service centers
$serviceCenters = ServiceCenter::find()->all();
$serviceCenterList = ArrayHelper::map($serviceCenters, 'id', 'name');
$this->registerJsFile(
    '@web/js/maintenance.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

?>

<div class="maintenance-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false
    ]); ?>
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
    
    <?php
    echo $form->field($model, "client_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[$model->client_id=>$model->client ? $model->client->name : ''],
        'options' => ['placeholder' => 'اختر  .....'],
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


    <?= $form->field($model, 'product_name')->textInput() ?>

    <?= $form->field($model, 'item_count')->textInput() ?>

    <?= $form->field($model, 'client_note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList($model::statusArray) ?>
   

 
    <?php
    $url = \yii\helpers\Url::to(['service-center/list']);
    echo $form->field($model, "service_center_id")->widget(Select2::classname(), [
        'data' =>[$model->service_center_id=>$model->serviceCenter?$model->serviceCenter->name :''],
        'options' => ['placeholder' => 'اختر  .....'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new \yii\web\JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                'results' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                'cache' => true

            ],
            'escapeMarkup' => new \yii\web\JsExpression('function (markup) { return markup; }'),
            'templateResult' => new \yii\web\JsExpression('function(product) { return product.text; }'),
            'templateSelection' => new \yii\web\JsExpression('function (product) { return product.text; }'),
        ],
    ]);
    ?>

    <?= $form->field($model, 'maintenance_note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'maintenance_cost')->textInput() ?>

    <?= $form->field($model, 'amount_paid')->textInput() ?>

    <?= $form->field($model, 'cost_difference')->textInput(['readonly' => true]) ?>

   
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success'])  ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

