<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Presence $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="presence-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList( [''=>'اختر ....'] + \yii\helpers\ArrayHelper::map(\common\models\User::find()->all(), 'id', 'full_name')); ?>

    <?=\kartik\datetime\DateTimePicker::widget([
        'model' => $model,
        'attribute' => 'time',
        'language' => 'ar',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-m-d H:i'
        ]
    ])?>

    <?= $form->field($model, 'type')->dropDownList($model::typesArray); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success'])  ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
