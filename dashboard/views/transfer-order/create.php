<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TransferOrder */

$this->title = Yii::t('app', 'Create Transfer Order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Transfer Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
