<?php

/**
 * @var LoginForm $model
 * @var string $content
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\ActiveForm;

\mortezakarimi\gentelellartl\assets\AnimateCssAsset::register($this);
$bundle = mortezakarimi\gentelellartl\assets\Asset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<!-- /header content -->
<body class="login">
<?php $this->beginBody(); ?>
<div>
    <style>
        input{
            direction: rtl;
        }
    </style>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'errorOptions' => [
                            'encode' => false,
                        ]
                    ]
                ]); ?>
                <h1> مجموعة وجدي للاعمار </h1>
                <div class="row">
                    <?= $form->field($model, 'username')->textInput(['placeholder' => "اسم المستخدم", 'autocomplete' => 'off'])->label(false) ?>
                </div>
                <div class="row">
                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => "كلمة المرور", 'autocomplete' => 'off'])->label(false) ?>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11">
                        <?= Html::submitButton('تسجيل دخول', ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <div class="clearfix"></div>


                <?php ActiveForm::end(); ?>
            </section>
        </div>


    </div>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
