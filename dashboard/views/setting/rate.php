<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \common\models\ConfigurationForm */
/* @var $this \yii\web\View */

$this->title = Yii::t('app', 'Manage Application Settings');
?>
<b><h2>اعدادات النسب</h2></b>
<?php $form = ActiveForm::begin(); ?>

<?php echo $form->field($model, 'price_profit_rate_1'); ?>
<?php echo $form->field($model, 'price_profit_rate_2'); ?>
<?php echo $form->field($model, 'price_profit_rate_3'); ?>
<?php echo $form->field($model, 'price_profit_rate_4'); ?>


<?php echo Html::submitButton(Yii::t('app', 'حفظ'), ['class' => 'btn btn-success'])  ?>

<?php ActiveForm::end(); ?>