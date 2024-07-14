<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ServiceCenter $model */

$this->title = 'انشاء مركز صيانة';
$this->params['breadcrumbs'][] = ['label' => 'Service Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-center-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
