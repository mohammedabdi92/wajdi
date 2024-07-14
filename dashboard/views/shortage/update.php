<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Shortage $model */

$this->title = 'تعديل نقص: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shortages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="shortage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
