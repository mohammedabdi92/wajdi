<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use dashboard\admin\AutocompleteAsset;

/* @var $this yii\web\View */
/* @var $model dashboard\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'ruleName')->textInput(['id'=>'rule-name']) ?>


    <div class="form-group">
        <?php
        echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-admin', 'Create') : Yii::t('rbac-admin', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',])
        ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
AutocompleteAsset::register($this);

$options = Json::htmlEncode([
    'source' => array_keys(Yii::$app->authManager->getRules())
]);
$this->registerJs("$('#rule-name').autocomplete($options);");