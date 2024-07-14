<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ServiceCenter $model */

$this->title = 'تعديل: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Service Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-center-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
