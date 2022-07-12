<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
/* @var $model_product common\models\OrderProduct */

$this->registerJsFile(
    '@web/js/inventory.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(['enableClientValidation'=>false,'id' => 'dynamic-form']); ?>
    <?= $form->field($model, 'customer_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Customer::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'store_id')->dropDownList(\common\components\Constants::storeArray); ?>

    <?= $form->field($model, 'total_amount')->textInput(['readonly' => true]) ?>


    <div class=" col-md-12">

        <div class="panel panel-default">
            <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i> المواد</h4></div>
            <div class="panel-body">
                <?php \wbraganca\dynamicform\DynamicFormWidget::begin([
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
                    <?php foreach ($model_product

                                   as $i => $modelAddress): ?>
                    <div class="item panel panel-default"><!-- widgetBody -->
                        <div class="panel-heading">
                            <h3 class="panel-title pull-right">مادة</h3>
                            <div class="pull-left">
                                <button type="button" class="add-item btn btn-success btn-xs"><i
                                            class="glyphicon glyphicon-plus"></i></button>
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
                            }
                            ?>

                            <div class="row">
                                <div class="col-sm-3">
                                    <?= $form->field($modelAddress, "[{$i}]product_id")->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Product::find()->all(), 'id', 'title')) ?>
                                </div>
                                <div class="col-sm-3">
                                    <?= $form->field($modelAddress, "[{$i}]count")->textInput() ?>
                                </div>
                            </div><!-- .row -->

                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php \wbraganca\dynamicform\DynamicFormWidget::end(); ?>
                </div>
            </div>
        </div>


        <div class=" col-md-12">


            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    </div>

