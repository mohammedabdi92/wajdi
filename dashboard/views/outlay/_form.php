<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Outlay */
/* @var $form yii\widgets\ActiveForm */


//print_r($model->pull_date);die;
?>

<div class="outlay-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
    <?= $form->field($model, 'amount')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'store_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\Store::find()->where(['status'=>1])->all(), 'id', 'name')); ?>
        </div>
    </div>

    <div class="row">

        <?php if(!Yii::$app->user->can('تعديل تاريخ المصروفات')):?>
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
    <div class="col-md-6">
        <?= $form->field($model, 'user_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>
    </div>

        <div class="col-md-6">
            <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>
        </div>
    </div>






    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
