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
            <?php
            $stores = [];
            if(Yii::$app->user->can('كل المحلات'))
            {
                $stores = \common\models\Store::find()->where(['status'=>1])->all();
            }else{
                $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
            }
            $single_store = null ;
            if(count($stores) == 1)
            {
                $single_store = $stores[0]->id;
            }
            if(empty($model->store_id))
            {
                $model->store_id = $single_store;
            }

            echo $form->field($model, 'store_id')->dropDownList([''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name'));
            ?>
        </div>
    </div>

    <div class="row">

        <?php if(Yii::$app->user->can('تعديل تاريخ المصروفات')):?>
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
