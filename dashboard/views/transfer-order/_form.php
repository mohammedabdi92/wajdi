<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TransferOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name')); ?>

    <?= $form->field($model, 'to')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name')); ?>
    <?php
    echo $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Product::find()->all(), 'id', 'title'),
        'options' => ['placeholder' => 'اختر نوع العد .....','onchange' => 'productChange(this)'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <?= $form->field($model, 'count')->textInput() ?>





    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
