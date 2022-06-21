<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrder */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (!isset($form)) {
    $form = new ActiveForm();
} ?>


    <div class="x_panel" style="">
        <div class="x_content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <?= $form->field($model, 'inventoryOrderProducts[][product_id]')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Product::find()->all(), 'id', 'title'))->label('العنصر') ?>
                    </div>
                    <div class="col-sm-4">
                        <?= $form->field($model, 'inventoryOrderProducts[][count]')->textInput()->label('العدد') ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
