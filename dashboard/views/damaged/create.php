<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Damaged */

$this->title = Yii::t('app', 'Create Damaged');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Damageds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="damaged-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
