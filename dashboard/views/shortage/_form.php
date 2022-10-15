<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Shortage $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shortage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>

    <?php
        echo $form->field($model, "product_id")->widget(\kartik\select2\Select2::classname(), [
            'data' =>[''=>"اختر ....."]+\yii\helpers\ArrayHelper::map(\common\models\Product::find()->where(['status'=>1])->all(), 'id', 'title'),
            'options' => ['placeholder' => 'اختر .....'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
