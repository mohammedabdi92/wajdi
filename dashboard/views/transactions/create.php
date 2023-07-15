<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Transactions $model */

$this->title = 'انشاء سداد';
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transactions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
