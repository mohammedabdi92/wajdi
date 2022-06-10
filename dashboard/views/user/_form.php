<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?= Html::a(Yii::t('app', 'رجوع'),$model->isNewRecord?'index' :Yii::$app->request->referrer, [
    'class' => 'btn btn-primary',

]) ?>
<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'full_name')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList($model::typeArray); ?>
    <?= $form->field($model, 'store_id')->dropDownList(\common\components\Constants::storeArray); ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>
    * اذا قمت بتعبئة هذا الحقل سيتغير كلمت السر للمستخدم
    <?= $form->field($model, 'password_text')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
