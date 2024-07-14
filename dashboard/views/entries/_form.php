<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Entries $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $stores = [];
    if(Yii::$app->user->can('كل المحلات'))
    {
        $stores = \common\models\Store::find()->where(['status'=>1])->all();
    }else{
        $stores = \common\models\Store::find()->where(['status'=>1,'id'=>Yii::$app->user->identity->stores])->all();
    }
    echo $form->field($model, 'store_id')->widget(\kartik\select2\Select2::classname(), [
        'data' =>[''=>'اختر المحل ....']+\yii\helpers\ArrayHelper::map($stores, 'id', 'name'),
        'options' => ['placeholder' => 'اختر المحل .....'  ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <?= $form->field($model, 'amount')->textInput() ?>
    <label>تاريخ الادخال</label>
    <?=   \kartik\date\DatePicker::widget([
        'model' => $model,
        'attribute' => 'put_date',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-m-d '
        ]
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton('حفظ', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
