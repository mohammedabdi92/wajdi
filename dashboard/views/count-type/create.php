<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CountType */

$this->title = Yii::t('app', 'انشاء نوع عد');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Count Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="count-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
