<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FinancialWithdrawal */

$this->title = Yii::t('app', 'انشاء مسحوبات من الصندوق');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="financial-withdrawal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
