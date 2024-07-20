<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Store $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>
    <?= $form->field($model, 'inv_footer_left')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'inv_footer_right')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
