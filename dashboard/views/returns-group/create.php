<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ReturnsGroup $model */

$this->title = Yii::t('app', 'انشاء مرجع');
$this->params['breadcrumbs'][] = ['label' => 'Returns Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="returns-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'model_product' => $model_product,
    ]) ?>

</div>
