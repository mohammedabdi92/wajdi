<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FinancialWithdrawal */
/* @var $form yii\widgets\ActiveForm */

$pull_date = $model->pull_date;
if(empty($pull_date))
{
    $pull_date = date('Y-m-d');
}

?>

<div class="outlay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput() ?>
    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
    <?= $form->field($model, 'status')->dropDownList($model::statusArray); ?>
    <?= $form->field($model, 'user_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>

    <?php if(Yii::$app->user->can('تعديل تاريخ المسحوبات')):?>
        <div class="col-md-6">
            <label>تاريخ السحب</label>
            <?=   \kartik\date\DatePicker::widget([
                'model' => $model,
                'attribute' => 'pull_date',
                'pluginOptions' => [

                    'autoclose' => true,
                    'format' => 'yyyy-m-d '
                ]
            ]); ?>
        </div>
    <?php endif;?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
