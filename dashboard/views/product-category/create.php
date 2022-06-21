<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategory */

$this->title = Yii::t('app', 'Create Product Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Product Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

    <h1 style="padding-bottom: 10px;padding-top: 10px;"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
