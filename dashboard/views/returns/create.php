<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Returns */

$this->title = Yii::t('app', 'انشاء مرجع');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Returns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="returns-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
