<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\InventoryOrder */
/* @var $model_product common\models\InventoryOrderProduct */

$this->title = Yii::t('app', 'انشاء طلب مورد');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Inventory Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-order-create">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_product' => $model_product,
    ]) ?>

</div>
