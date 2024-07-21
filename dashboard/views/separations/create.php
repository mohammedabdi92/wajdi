<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Separations $model */

$this->title = 'انشاء طلب فرط وجمع';
$this->params['breadcrumbs'][] = ['label' => 'Separations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="separations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
