<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Outlay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="outlay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput() ?>
    <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
    <?= $form->field($model, 'user_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>

    <label>تاريخ السحب</label>
    <?=   \kartik\date\DatePicker::widget([
        'model' => $model,
        'attribute' => 'pull_date',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-m-d '
        ]
    ]); ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
