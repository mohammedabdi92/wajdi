<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TransferOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
