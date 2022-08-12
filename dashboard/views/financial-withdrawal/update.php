<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FinancialWithdrawal */

$this->title = Yii::t('app', 'Update Financial Withdrawal: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Financial Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="financial-withdrawal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
