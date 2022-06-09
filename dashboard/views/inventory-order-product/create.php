<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrderProduct */

$this->title = Yii::t('app', 'Create Inventory Order Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory Order Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-order-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
