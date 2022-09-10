<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Presence $model */

$this->title = 'تعديل بصمة : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Presences', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="presence-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
