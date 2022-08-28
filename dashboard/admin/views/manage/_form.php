<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use dashboard\admin\AutocompleteAsset;
use dashboard\admin\components\MenuHelper;

/* @var $this yii\web\View */
/* @var $model dashboard\admin\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
//print_r($model->menus);die;
?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
    
    <div class="permission-menu">
        <h1>Menus Permission</h1>
        <?=MenuHelper::menuHtml($model->menus, $form, $model)?>
    </div>
    <br>
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
?>

<?php

$this->registerJs("
  $( document ).ready(function() {
        $('.permission-menu a').on('click', function(){
            var checkbox = $(this).children('div').children('input[type=\"checkbox\"]');
            checkbox.prop('checked', !checkbox.prop('checked'));

            if (checkbox.prop('checked')) {
                $(this).removeClass('unchecked-background');
                $(this).addClass('checked-background');
            }else{
                $(this).removeClass('checked-background');
                $(this).addClass('unchecked-background');
            }
        });
    });
", yii\web\View::POS_END);
?>
<script>




</script>