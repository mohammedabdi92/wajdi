<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Entries $model */

$this->title = 'انشاء مدخل';
$this->params['breadcrumbs'][] = ['label' => 'Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
