<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ReturnsGroup $model */

$this->title = 'تعديل :' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Returns Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="returns-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'model_product' => $model_product,
    ]) ?>

</div>
