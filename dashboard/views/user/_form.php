<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dashboard\admin\AdminAsset;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
list(,$url) = Yii::$app->assetManager->publish('@dashboard/admin/assets');
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
    <?= $form->field($model, 'store_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Store::find()->all(), 'id', 'name')); ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>
    * اذا قمت بتعبئة هذا الحقل سيتغير كلمت السر للمستخدم
    <?= $form->field($model, 'password_text')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
    <div class="assignment-index">
        <div class="row">
            <div class="col-lg-3">
                <p><b>الصلاحيات المتاحة</b></p>
                <select id="list-avaliable" multiple size="12" style="width: 100%">
                </select>
            </div>
            <div class="col-lg-1">
                <br><br><br><br>
                <a href="#" id="btn-assign" class="btn btn-success">&gt;&gt;</a><br><br>
                <a href="#" id="btn-revoke" class="btn btn-danger">&lt;&lt;</a>
            </div>
            <div class="col-lg-3">
                <p><b>الصلاحيات الفعالة</b></p>
                <select id="list-assigned" multiple size="12" style="width: 100%">
                </select>
            </div>
        </div>
    </div>
    <br>
<?php
AdminAsset::register($this);
$properties = Json::htmlEncode([
    'userId' => $model->id,
    'assignUrl' => Url::to(['assign']),
    'searchUrl' => Url::to(['search']),
]);
$js = <<<JS
yii.admin.initProperties({$properties});

$('#search-avaliable').keydown(function () {
    yii.admin.searchAssignmet('avaliable');
});
$('#search-assigned').keydown(function () {
    yii.admin.searchAssignmet('assigned');
});
$('#btn-assign').click(function () {
    yii.admin.assign('assign');
    return false;
});
$('#btn-revoke').click(function () {
    yii.admin.assign('revoke');
    return false;
});

yii.admin.searchAssignmet('avaliable', true);
yii.admin.searchAssignmet('assigned', true);
JS;
$this->registerJs($js);