<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Transactions $model */
/** @var yii\widgets\ActiveForm $form */
$stores = [];
if(Yii::$app->user->can('كل المحلات'))
{
    $stores = \common\models\Store::find()->where(['status'=>1])->all();
}else{
    $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
}
?>

<div class="transactions-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'store_id')->dropDownList([''=>'المحل ... ']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name')) ?>


    <?php
    echo $form->field($model, "customer_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->select("id,name")->all(), 'id', 'name'),
        'options' => ['placeholder' => 'اختر نوع العد .....'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
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
 ?>


    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'type')->hiddenInput(['value'=> \common\models\Transactions::TYPE_REPAYMENT])->label(' ') ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
