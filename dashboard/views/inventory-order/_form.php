<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrder */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="inventory-order-form">

    <?php $form = ActiveForm::begin(); ?>
    <div id="product_container">
    <?php echo $this->render('_product', ['model' => $model, 'form'=>$form]); ?>
    </div>

    <?php  \demogorgorn\ajax\AjaxSubmitButton::begin([
                            'label' => 'اضافة +',
                            'ajaxOptions' => [
                                'type'=>'POST',
                                'url'=>'ajax-product',
                                'success' => new \yii\web\JsExpression('function(html){
                                
                                    if(html !== "")
                                    {
                                        $("#product_container").append(html);
                                    }
                                    
                                }'),
                            ],
                            'options' => ['id'=>'post_price_btn','class' => 'btn btn-info pull-right'],
                        ]);
    \demogorgorn\ajax\AjaxSubmitButton::end();?>
    <div class="col-md-12">


    <?= $form->field($model, 'supplier_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Supplier::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'store_id')->dropDownList(\common\components\Constants::storeArray); ?>

    <?= $form->field($model, 'total_cost')->textInput() ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>



    <?php ActiveForm::end(); ?>

</div>
