<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Outlay */

$this->title = Yii::t('app', 'انشاء مصروفات');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Outlays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="outlay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
